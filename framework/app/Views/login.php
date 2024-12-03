<!DOCTYPE html>
<html>
<head>
    <title>Slide Navbar</title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>
<div class="main">
<input type="checkbox" id="chk" aria-hidden="true">

    <div class="signup">
        <form action="/save" method="post">
            <label for="chk" aria-hidden="true">Sign up</label>
            <input type="text" name="name" placeholder="User name" required="">
            <input type="email" name="email" placeholder="Email" required="">
            <input type="password" name="password" placeholder="Password" required="">
            <button class="custom-button">Sign up</button>
        </form>
    </div>

    <div class="login">
        <form action="/check" method="post">
            <label for="chk" aria-hidden="true">Login</label>
            <input type="email" name="email" placeholder="Email" required="">
            <input type="password" name="password" placeholder="Password" required="">
            <button class="custom-button">Login</button>
        </form>
    </div>
</div>

<?php if (session()->getFlashdata('error')): ?>
    <script>
        Swal.fire({
            title: 'Error!',
            text: '<?php echo session()->getFlashdata('error'); ?>',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    </script>
<?php endif; ?>
<?php if (session()->getFlashdata('success')): ?>
    <script>
        Swal.fire({
            title: 'successfully register😊',
            text: '<?php echo session()->getFlashdata('error'); ?>',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    </script>
<?php endif; ?>

</body>
</html>
