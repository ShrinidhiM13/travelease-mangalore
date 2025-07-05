<?php
session_start();
require_once '../config/db.php';

// Admin access check
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$errors = [];
$success = '';
$edit_mode = false;
$event = [
    'id' => '',
    'title' => '',
    'description' => '',
    'event_date' => '',
    'youtube_url' => '',
    'image1' => '',
    'image2' => '',
    'image3' => '',
    'image4' => '',
    'image5' => '',
];

// Handle delete
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Delete event images from uploads folder
    $stmtDel = $pdo->prepare("SELECT image1,image2,image3,image4,image5 FROM events WHERE id = ?");
    $stmtDel->execute([$id]);
    $imgs = $stmtDel->fetch();

    if ($imgs) {
        foreach ($imgs as $img) {
            if ($img && file_exists("../uploads/events/$img")) {
                unlink("../uploads/events/$img");
            }
        }
    }

    // Delete from DB
    $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: manage-events.php?deleted=1');
    exit;
}

// Handle edit load
if (isset($_GET['edit'])) {
    $edit_mode = true;
    $id = intval($_GET['edit']);
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->execute([$id]);
    $event = $stmt->fetch();
    if (!$event) {
        header('Location: manage-events.php');
        exit;
    }
}

// Handle form submit for add/edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $event_date = $_POST['event_date'] ?? '';
    $youtube_url = trim($_POST['youtube_url'] ?? '');
    $id = intval($_POST['id'] ?? 0);

    // Basic validation
    if (!$title) {
        $errors[] = 'Title is required.';
    }
    if (!$event_date) {
        $errors[] = 'Event date is required.';
    }
    // Validate YouTube URL pattern (simple check)
    if ($youtube_url && !preg_match('/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/.+$/', $youtube_url)) {
        $errors[] = 'Please enter a valid YouTube URL.';
    }

    // Handle image uploads (up to 5)
    $uploadedImages = [];
    $imageFields = ['image1', 'image2', 'image3', 'image4', 'image5'];
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxSize = 2 * 1024 * 1024; // 2MB max

    foreach ($imageFields as $field) {
        if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES[$field];
            if (!in_array($file['type'], $allowedTypes)) {
                $errors[] = ucfirst($field) . ' must be a JPG, PNG or GIF image.';
            } elseif ($file['size'] > $maxSize) {
                $errors[] = ucfirst($field) . ' exceeds max size of 2MB.';
            } else {
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $newName = uniqid($field . '_') . '.' . $ext;
                $destination = "../uploads/events/$newName";
                if (move_uploaded_file($file['tmp_name'], $destination)) {
                    $uploadedImages[$field] = $newName;
                } else {
                    $errors[] = "Failed to upload $field.";
                }
            }
        }
    }

    if (empty($errors)) {
        // If editing: fetch old images to keep if no new upload for that image slot
        if ($id) {
            $stmtOld = $pdo->prepare("SELECT image1,image2,image3,image4,image5 FROM events WHERE id = ?");
            $stmtOld->execute([$id]);
            $oldImgs = $stmtOld->fetch();
        } else {
            $oldImgs = [];
        }

        // Prepare data to insert/update
        $data = [];
        foreach ($imageFields as $field) {
            if (isset($uploadedImages[$field])) {
                $data[$field] = $uploadedImages[$field];
                // If editing, delete old image if new uploaded
                if ($id && !empty($oldImgs[$field]) && file_exists("../uploads/events/" . $oldImgs[$field])) {
                    unlink("../uploads/events/" . $oldImgs[$field]);
                }
            } else {
                // keep old image if any
                $data[$field] = $oldImgs[$field] ?? null;
            }
        }

        if ($id) {
            // Update event
            $sql = "UPDATE events SET title=?, description=?, event_date=?, youtube_url=?, image1=?, image2=?, image3=?, image4=?, image5=? WHERE id=?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $title,
                $description,
                $event_date,
                $youtube_url,
                $data['image1'],
                $data['image2'],
                $data['image3'],
                $data['image4'],
                $data['image5'],
                $id
            ]);
            $success = "Event updated successfully.";
        } else {
            // Insert new event
            $sql = "INSERT INTO events (title, description, event_date, youtube_url, image1, image2, image3, image4, image5) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $title,
                $description,
                $event_date,
                $youtube_url,
                $data['image1'],
                $data['image2'],
                $data['image3'],
                $data['image4'],
                $data['image5']
            ]);
            $success = "New event added successfully.";
        }
        // Reset form
        $event = [
            'id' => '',
            'title' => '',
            'description' => '',
            'event_date' => '',
            'youtube_url' => '',
            'image1' => '',
            'image2' => '',
            'image3' => '',
            'image4' => '',
            'image5' => '',
        ];
        $edit_mode = false;
    } else {
        // Refill form fields after error
        $event = [
            'id' => $id,
            'title' => $title,
            'description' => $description,
            'event_date' => $event_date,
            'youtube_url' => $youtube_url,
            'image1' => $event['image1'],
            'image2' => $event['image2'],
            'image3' => $event['image3'],
            'image4' => $event['image4'],
            'image5' => $event['image5'],
        ];
    }
}

// Fetch all events
$stmt = $pdo->query("SELECT * FROM events ORDER BY event_date DESC");
$events = $stmt->fetchAll();

function embedYoutubeThumbnail($url) {
    // Extract video id
    if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([\w\-]+)/', $url, $matches)) {
        return "https://img.youtube.com/vi/" . $matches[1] . "/default.jpg";
    }
    return '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Manage Events - Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
    .event-images img {
        width: 60px;
        height: 40px;
        object-fit: cover;
        margin-right: 5px;
        border-radius: 4px;
        border: 1px solid #ddd;
    }
    .youtube-embed {
        max-width: 160px;
    }
</style>
</head>
<body>
    <?php include 'nav.php'; ?>
<div class="container my-4">
    <h2>Manage Events</h2>
    
    <?php if ($errors): ?>
        <div class="alert alert-danger">
            <ul><?php foreach ($errors as $e) echo "<li>" . htmlspecialchars($e) . "</li>"; ?></ul>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header"><?= $edit_mode ? "Edit Event" : "Add New Event" ?></div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= htmlspecialchars($event['id']) ?>" />
                <div class="mb-3">
                    <label for="title" class="form-label">Event Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" id="title" class="form-control" required value="<?= htmlspecialchars($event['title']) ?>" />
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" id="description" class="form-control" rows="4"><?= htmlspecialchars($event['description']) ?></textarea>
                </div>

                <div class="mb-3">
                    <label for="event_date" class="form-label">Event Date <span class="text-danger">*</span></label>
                    <input type="date" name="event_date" id="event_date" class="form-control" required value="<?= htmlspecialchars($event['event_date']) ?>" />
                </div>

                <div class="mb-3">
                    <label for="youtube_url" class="form-label">YouTube Video URL</label>
                    <input type="url" name="youtube_url" id="youtube_url" class="form-control" placeholder="https://www.youtube.com/watch?v=..." value="<?= htmlspecialchars($event['youtube_url']) ?>" />
                </div>

                <div class="mb-3">
                    <label class="form-label">Upload Images (max 5) - JPG, PNG, GIF, max 2MB each</label>
                    <div class="row g-3">
                        <?php
                        for ($i = 1; $i <= 5; $i++):
                            $imgField = "image$i";
                            $imgVal = $event[$imgField] ?? '';
                        ?>
                        <div class="col-md-2 text-center">
                            <?php if ($imgVal && file_exists("../uploads/events/$imgVal")): ?>
                                <img src="../uploads/events/<?= htmlspecialchars($imgVal) ?>" alt="Event Image <?= $i ?>" class="img-thumbnail mb-1" />
                            <?php else: ?>
                                <div class="border rounded p-3 text-muted mb-1" style="height:80px; font-size:0.8rem;">No Image</div>
                            <?php endif; ?>
                            <input type="file" name="<?= $imgField ?>"  class="form-control form-control-sm" />
                        </div>
                        <?php endfor; ?>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary"><?= $edit_mode ? "Update Event" : "Add Event" ?></button>
                <?php if ($edit_mode): ?>
                <a href="manage-events.php" class="btn btn-secondary ms-2">Cancel Edit</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <h3>Existing Events</h3>
    <?php if (count($events) === 0): ?>
        <div class="alert alert-info">No events found.</div>
    <?php else: ?>
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Event Date</th>
                <th>Description</th>
                <th>YouTube</th>
                <th>Images</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($events as $index => $ev): ?>
            <tr>
                <td><?= $index + 1 ?></td>
                <td><?= htmlspecialchars($ev['title']) ?></td>
                <td><?= htmlspecialchars($ev['event_date']) ?></td>
                <td style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    <?= htmlspecialchars($ev['description']) ?>
                </td>
                <td>
                    <?php if ($ev['youtube_url']): ?>
                        <a href="<?= htmlspecialchars($ev['youtube_url']) ?>" target="_blank" class="btn btn-sm btn-outline-danger">Watch</a>
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </td>
                <td class="event-images">
                    <?php
                    for ($i = 1; $i <= 5; $i++) {
                        $imgField = "image$i";
                        if (!empty($ev[$imgField]) && file_exists("../uploads/events/" . $ev[$imgField])) {
                            echo '<img src="../uploads/events/' . htmlspecialchars($ev[$imgField]) . '" alt="Event Image">';
                        }
                    }
                    ?>
                </td>
                <td>
                    <a href="?edit=<?= $ev['id'] ?>" class="btn btn-sm btn-warning mb-1">Edit</a>
                    <a href="?delete=<?= $ev['id'] ?>" class="btn btn-sm btn-danger mb-1" onclick="return confirm('Are you sure you want to delete this event?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>
</div>
</body>
</html>
