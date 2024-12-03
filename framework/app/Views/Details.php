<!DOCTYPE html>
<html lang="en">

<head>
    <title>User List</title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/footer.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Parkinsans:wght@300..800&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<style>
        .dataTables_filter input {
            margin-left: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
            border: 1px solid #ced4da;
            padding: 0.5em;
        }

        .dataTable thead th {
            text-align: center !important;
        }

        .dataTables_filter label {
            font-weight: bolder;
        }

        .dataTables_filter input:hover,
        .dataTables_filter input:active {
            border: 2px solid green !important;
        }

        #todoTable_filter {
            margin-bottom: 10px;
        }

        .modal {
            z-index: 1055 !important;
            /* Ensure modal is above other elements */
        }

        .select2-container {
            z-index: 1060 !important;
            /* Ensure Select2 container is above other elements */
        }

        .select2-dropdown {
            z-index: 1061 !important;
            /* Ensure Select2 dropdown is above other elements */
        }
    body {
        font-family: "Parkinsans", sans-serif;
  font-weight: 500;
  font-style: normal;
}
    </style>
<body>
    <header>
        <?= $this->include('navbar') ?>

    </header>
    <div class="container mt-5 mb-3" style="max-width: 95%;">
        <!-- Alerts -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show w-75 mx-auto mt-3" role="alert">
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger alert-dismissible fade show w-75 mx-auto mt-3" role="alert">
                <?= session()->getFlashdata('errors') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Action Buttons -->
        <!-- <div class="d-flex justify-content-end gap-2 mb-4">
            <form action="/upload/save" method="post" enctype="multipart/form-data" class="d-flex align-items-center">
                <input type="file" class="form-control me-2" id="file" name="file" style="width: 150px;display:none" required>
                <button type="submit" id="uploadbtn" class="btn btn-primary me-2"><i class="fa-solid fa-upload"></i>Upload</button>
            </form>
            <a href="/" class="btn btn-success me-2"><i class="fa-solid fa-address-book"></i>Add Employee</a>
            <a href="/download-csv" class="btn btn-success me-2"><i class="fa-solid fa-download"></i> Download file</a>
            <a href="/logout" class="btn btn-danger"><i class="fa-solid fa-right-from-bracket"></i>Logout</a>
        </div>

        modal start -->
         <!-- <div class="d-flex justify-content-start gap-2 mb-2">
        <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
        <i class="fa-solid fa-filter"></i> Filter data
        </button>
        <a href="/details" class="btn btn-dark mb-2">Get allData</a></div> -->

        <!-- Modal -->
        
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <form action="/search-users" method="post">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Filter Users</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">


                            <div class="mb-3">
                                <label class="form-label">Search User by Name</label>
                                <select name="name" class="js-example-basic-single" style="width: 100%;">
                                    <option value="">filter by name</option>
                                    <?php foreach ($alldata as $item): ?>
                                        <option value="<?= $item['name'] ?>"><?= $item['name'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Search User by Email</label>
                                <select name="email" class="js-example-basic-single" style="width: 100%;">
                                    <option value="">Filter by email</option>
                                    <?php foreach ($alldata as $item): ?>
                                        <option value="<?= $item['email'] ?>"><?= $item['email'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>

                        </div>
                        </form>
                    </div>
                </div>
            </div>
        
  



<table class="table text-center mt-3" id="userTable" style="width: 100%; border: 1px solid black;">
    <thead class="table-dark">
                <tr>
                    <th>SR</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($data)): ?>
                    <?php $counter = 0;
                    foreach ($data as $user): $counter++; ?>
                        <tr>
                            <td><?= $counter ?></td>
                            <td><?= esc($user['name']); ?></td>
                            <td><?= esc($user['email']); ?></td>
                            <td>
                                <div class="d-flex justify-content-center gap-4">
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateModal" onclick="fetchUserData(<?= $user['id']; ?>)">
                                        <i class="fa-solid fa-user-pen"></i>
                                    </button>
                                    <a  onclick="message_alert(<?=$user['id']?>)" class="btn btn-danger">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No users found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Modal for User Details -->
        <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Update Data</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="updateForm" method="POST">
                            <div class="mb-3">
                                <label for="dataId" class="form-label">ID</label>
                                <input type="number" id="dataId" name="id" class="form-control" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="dataName" class="form-label">Name</label>
                                <input type="text" class="form-control" id="dataName" name="name" required>
                            </div>
                            <div class="mb-3">
                                <label for="dataEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="dataEmail" name="email" required>
                            </div>
                            <div class="mb-3">
                                <label for="dataPassword" class="form-label">Password</label>
                                <input type="text" class="form-control" id="dataPassword" name="password" required>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" id="btn1" class="btn btn-primary" onclick="updatedata()">
                                Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <?= $this->include('footer') ?>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#userTable').DataTable({
                "pageLength": 5,
                "lengthMenu": [
                    [5, 10, 25, -1],
                    [5, 10, 25, "All"]
                ]
            });

            $(document).ready(function() {
                $('.js-example-basic-single').select2({
                    dropdownParent: $('#exampleModal')
                });
               
            });
        });


        // Function to fetch user data for the modal
        function fetchUserData(id) {
            fetch(`/getUserById/${id}`)
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    // Populate the modal with user data
                    document.getElementById('dataId').value = data.id;
                    document.getElementById('dataName').value = data.name;
                    document.getElementById('dataEmail').value = data.email;
                    document.getElementById('dataPassword').value = data.password;
                    document.getElementById('updateForm').action = `/update/${data.id}`;
                })
                .catch(error => console.error('Error:', error));
        }
// upload
        document.getElementById('upload-link').addEventListener('click', function() {
    const fileInput = document.getElementById('file-input');
    fileInput.click();

    fileInput.addEventListener('change', function() {
      if (fileInput.files.length > 0) {
        fileInput.closest('form').submit();
      }
    })
  })


  function message_alert(id){
   
    Swal.fire({
  title: "Are you sure?",
  text: "You won't be able to revert this!",
  icon: "warning",
  showCancelButton: true,
  confirmButtonColor: "#3085d6",
  cancelButtonColor: "#d33",
  confirmButtonText: "Yes, delete it!"
}).then((result) => {
  if (result.isConfirmed) {
    setTimeout(()=>{
    Swal.fire({
      title: "Deleted!",
      text: "Your file has been deleted.",
      icon: "success"
     
    });
    window.location.href = `/delete/${id}`;
  },500);
}})}


function updatedata() {


    Swal.fire({
        position: "top-end",
        icon: "success",
        title: "Your work has been saved",
        showConfirmButton: false,
        timer: 2500 // The message will disappear after 1.5 seconds
    });


}

  
    </script>
</body>

</html>