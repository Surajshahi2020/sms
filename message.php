<!-- SweetAlert CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">

<?php
if (isset($_SESSION['status'])) 
{
    ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      Swal.fire({
        title: 'Hey!',
        text: "<?php echo $_SESSION['status']; ?>",
        icon: "<?php echo $_SESSION['msg_type']; ?>",  // 'success', 'error', 'info', 'warning', 'question'
        timer: 4000,               // auto close after 8 seconds
        timerProgressBar: true,    // optional: shows a progress bar
        showConfirmButton: false,  // hides the OK button
        allowOutsideClick: true    // optional: allows closing by clicking outside
      });
    </script>
    <?php
    unset($_SESSION['status']);
}
?>
