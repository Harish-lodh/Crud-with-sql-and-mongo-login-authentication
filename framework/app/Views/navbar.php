<nav class="navbar navbar-expand-lg" style="background-color: #e3f2fd;">
  <div class="container-fluid ">
  
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0 ">
        <li class="nav-item ms-5">
          <a class="nav-link active" data-bs-toggle="modal" data-bs-target="#exampleModal" aria-current="page" href="#"><i class="fa-solid fa-filter"></i>Filter</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="/details">ShowAllData</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle  active" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Action
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="/"><i class="fa-solid fa-address-book"></i> Add employee</a></li>
            <li><a class="dropdown-item" id="upload-link"><i class="fa-solid fa-upload"></i> Upload file</a>
              <form action="/upload/save" method="post" enctype="multipart/form-data" id="upload-form" style="display: none;"> 
                <input type="file" class="form-control" id="file-input" name="file" required> </form>
              </form>
            </li>
            <li><a class="dropdown-item" href="/download-csv"><i class="fa-solid fa-download"></i> Download file</a></li>
            <li>
              <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="/logout"><i class="fa-solid fa-right-from-bracket"></i> Log Out</a></li>
          </ul>
        </li>

      </ul>
      <i class="fa-regular fa-user fa-2x " style="margin-left:25px"> </i>
       <a class="navbar-brand" href="#"> Welcome <?php echo session()->get('admin'); ?></a>
    </div>
  </div>
</nav>