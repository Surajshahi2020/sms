<?php
include 'includes/authentication.php';
include 'includes/header.php';
include 'includes/topbar.php';
include 'includes/sidebar.php';
?>

<style>
.content-container {
    margin-top: 60px; /* space below topbar */
}
</style>

<div class="content-wrapper">
    <section class="content">
        <div class="container content-container">

            <?php include 'message.php'; ?>

            <div class="card">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" id="notificationForm" action="alerts_code.php">
                        <div class="mb-3">
                            <label>शीर्षक</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>सन्देश</label>
                            <div id="editor" style="height: 200px;"></div>
                            <input type="hidden" name="message" id="message">
                        </div>

                        <div class="mb-3">
                            <label>प्रकार</label>
                            <select name="type" class="form-control" required>
                                <option value="">-- Select Type --</option>
                                <option value="info">Info</option>
                                <option value="success">Success</option>
                                <option value="warning">Warning</option>
                                <option value="danger">Danger</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Banner / Image / Video</label>
                            <input type="file" name="banner" class="form-control" required>
                        </div>

                        <button type="submit" name="addNotification" class="btn btn-primary">पठाउनुहोस्</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
include 'includes/footer.php';
include 'includes/script.php';
?>

<!-- Quill JS and CSS -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    var quill = new Quill('#editor', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'font': [] }, { 'size': [] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'header': '1'}, { 'header': '2'}, 'blockquote', 'code-block'],
                [{ 'list': 'ordered'}, { 'list': 'bullet'}, { 'indent': '-1'}, { 'indent': '+1' }],
                [{ 'align': [] }],
                ['link'],
                ['clean']
            ]
        }
    });

    // Copy Quill content to hidden input and validate
    document.getElementById('notificationForm').addEventListener('submit', function(e) {
        var messageContent = quill.root.innerHTML.trim();
        document.getElementById('message').value = messageContent;

        // Strip HTML tags to check if empty
        var textOnly = quill.getText().trim();
        if (textOnly === "") {
            e.preventDefault(); // Stop form submission
            alert("Message field is required!");
            quill.focus();
            return false;
        }
    });
});
</script>