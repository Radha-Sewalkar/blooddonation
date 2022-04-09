<?php
session_start();
include 'inventory_sheet/partials/_ConnectionDB.php';
if (isset($_SESSION['C_loggedin'])) {
  if ($_SESSION['C_loggedin'] == true) {
    $status = "UPDATE `users` SET `ONLINE` = '1' WHERE `users`.`SID` = $_SESSION[SID]";
    mysqli_query($Connect_DB, $status);
  }
}
$login_Table = "CREATE TABLE `LOGIN`(`LOGIN_ID` INT(6) NOT NULL AUTO_INCREMENT,
                                  `USERNAME` VARCHAR(50) UNIQUE,
                                  `PASSWORD` VARCHAR(255) NOT NULL DEFAULT 'NONE',
                                  `PIN_CODE` VARCHAR(255) NOT NULL DEFAULT 'NONE',
                                  PRIMARY KEY (`LOGIN_ID`))ENGINE=InnoDB DEFAULT CHARSET=latin1";
$login_Query = mysqli_query($Connect_DB, $login_Table);



$User_Table = "CREATE TABLE `ISSUES`(`SID` INT(6) NOT NULL AUTO_INCREMENT,
                                  `NAME` VARCHAR(50) NOT NULL,
                                  `CONTACT` VARCHAR(50) NOT NULL,
                                  `CNIC` VARCHAR(50) NOT NULL UNIQUE,
                                  `ISSUE` VARCHAR(150) NOT NULL,
                                  PRIMARY KEY (`SID`))ENGINE=InnoDB DEFAULT CHARSET=latin1";

$U_Table_Query = mysqli_query($Connect_DB, $User_Table);

$gallery_Table = "CREATE TABLE `Gallery`(`SID` INT(6) NOT NULL AUTO_INCREMENT,
                                  `image_url` text NOT NULL DEFAULT 'G_1.jpg',
                                  `LOGIN_ID` INT DEFAULT null, FOREIGN KEY (`LOGIN_ID`) REFERENCES `LOGIN`(`LOGIN_ID`) ON DELETE SET NULL,
                                  PRIMARY KEY (`SID`))";
$gallery_Query = mysqli_query($Connect_DB, $gallery_Table);

$gal1 = "SELECT *FROM `Gallery`";
$r_gal = mysqli_query($Connect_DB, $gal1);
if ($gallery_Query or mysqli_num_rows($r_gal) === 0) {
  // echo mysqli_num_rows($r_gal);
  // echo "Hello";
  $gallery_Query_INSERT = "INSERT INTO `Gallery` (`image_url`) VALUES('G_1.jpg')";
  mysqli_query($Connect_DB, $gallery_Query_INSERT);
  $gallery_Query_INSERT1 = "INSERT INTO `Gallery` (`image_url`) VALUES('G_2.jpg')";
  mysqli_query($Connect_DB, $gallery_Query_INSERT1);
  $gallery_Query_INSERT2 = "INSERT INTO `Gallery` (`image_url`) VALUES('G_3.jpg')";
  mysqli_query($Connect_DB, $gallery_Query_INSERT2);
}

if ($U_Table_Query) {
  $User_FIRST_INSERT = "INSERT INTO ISSUES (`NAME`,`CONTACT`,`CNIC`,`ISSUE`) VALUES('NONE','****','35202-*******-9','NO ENTRY')";
  mysqli_query($Connect_DB, $User_FIRST_INSERT);
}
$check = 0;
$check1 = 0;
if (isset($_POST['r_submit'])) {
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $R_NAME = $_POST['r_name'];
    $R_CONTACT = $_POST['r_contact'];
    $R_CNIC = $_POST['r_cnic'];
    $R_ISSUE = $_POST['r_issue'];
    $User_FIRST_INSERT = "INSERT INTO ISSUES (`NAME`,`CONTACT`,`CNIC`,`ISSUE`) VALUES('$R_NAME','$R_CONTACT','$R_CNIC','$R_ISSUE')";
    $err = mysqli_query($Connect_DB, $User_FIRST_INSERT);
    if ($err) {
      $check = 1;
    } else {
      $check1 = 1;
    }
  }
}
?>
<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php
  include "partials/Web_Logo.php";
  include "partials/links.php";
  ?>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
  <link rel="stylesheet" href="/reddrop/css/style.css">


  <title>Donate the Blood</title>
</head>
<style>


.aim_style{font-size: 13px;
word-break: break-word;
/* margin-left: 20px; */
/* margin-right: 20px; */
font-weight: bolder;
margin: 5px;
padding: 10px;
font-family: inherit;
}


</style>
<body>
  <header>
    <?php
    $navCheck = 1;
    include 'partials/Contact_Bar.php';
    include 'partials/C_NavBar.php';
    if ($check) {
      echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
      Successfully Submitted.
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
    }
    if ($check1) {
      echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
      You already Registered your Issue. If you have another Issue please try again later.
<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>';
    }
    include 'partials/carosel.php';
    ?>
  </header>
  <main>
    <!-- Button trigger modal -->
    <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modalissue">
      Launch demo modal
    </button> -->

    <!-- Modal -->
    <div class="modal fade opacity_9" id="Modalissue" tabindex="-1" aria-labelledby="ModalissueLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content bg-secondary">
          <div class="modal-header">
            <h5 class="modal-title text-light" id="ModalissueLabel">Register Your Problems</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="/reddrop/index.php" method="post">
              <div class="mb-3">
                <input type="text" placeholder="Your Name*" required maxlength="30" class="form-control" name="r_name" id="exampleInputname" aria-describedby="nameHelp">
              </div>
              <div class="mb-3">
                <input type="text" placeholder="Your Contact*" required maxlength="15" class="form-control" name="r_contact" id="exampleInputcontact">
              </div>
              <div class="mb-3">
                <input type="text" placeholder="XXXXX-XXXXXXX-X" required minlength="15" maxlength="15" class="form-control" name="r_cnic" id="exampleInputcnic" oninvalid="this.setCustomValidity('Please use this format XXXXX-XXXXXXX-X')" oninput="this.setCustomValidity('')">
              </div>
              <div class="form-floating">
                <textarea class="form-control" name="r_issue" placeholder="Your Issues*" id="floatingissues"></textarea>
                <label for="floatingTextarea">Issues</label>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-dark rounded-pill" data-bs-dismiss="modal">Close</button>
                <button type="submit" name="r_submit" class="btn btn-primary rounded-pill">Submit</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>


    <div class="d-flex justify-content-center" id="event">
      <h2>Events
        <div class="row mb-3">
          <div class="bg-danger col-4 rounded zoom" style="height:3px;"></div>
          <div class="bg-light col rounded" style="height:3px"></div>
        </div>
      </h2>
    </div>
    <div class="px-5 table-responsive">
      <table class="table table-success table-striped table-hover">
        <thead>
          <tr>
            <th scope="col">SR#</th>
            <th scope="col">Event Type</th>
            <th scope="col">Contact</th>
            <th scope="col">Event Date</th>
            <th scope="col">Manager</th>
        
          </tr>
        </thead>
        <tbody>
          <?php
          $sql1 = "SELECT *FROM `event` left join `login` on `event`.`LOGIN_ID` = `LOGIN`.`LOGIN_ID`";
          $result1 = mysqli_query($Connect_DB, $sql1);
          $num = 0;
          $form = 0;
          if ($result1) {
            while ($row = mysqli_fetch_assoc($result1)) {
              $form += 1;
              if(!$row['USERNAME'])
              {
                $row['USERNAME'] = "Not Appointed";
              }
              echo "<tr>
                            <th scope='row'>" . $form . "</th>
                            <td>" . $row['EVENT_TYPE'] . "</td>
                            <td>" . $row['CONTACT'] . "</td>
                            <td>" . $row['DATE'] . "</td>
                            <td>" . $row['USERNAME'] . "</td>
          </tr>";
            }
          }
          else{
            echo "<tr>
                            <th scope='row'>1</th>
                            <td>No Event</td>
                            <td>No Event</td>
                            <td>No Event</td>
                            <td>No Event</td>
          </tr>";
          }
          ?>
        </tbody>
      </table>
    </div>





    <div class="wrapper my-2 text-center" style="font-family: 'New Tegomin', serif;">
      <h2 class="text-Dark">Donate Blood <span class="box text-danger"></span></h2>
    </div>
    <div class="d-flex justify-content-center">
      <div class="m-4">
        <div class="card mb-3 bg-ccc" style="max-width: 1040px;">
          <div class="row g-0 p-4">
            <div class="col-md-4 mt-5 rounded">
              <img src="/reddrop/pictures/p_5.png" class="rounded blur" alt="..." style="width: 300px;">
            </div>
            <div class="col-md-8">
              <div class="card-body">
                <h4 class="card-title">Donate the Blood</h4>
                <div class="row mb-3 px-2">
                  <div class="bg-danger col-1 rounded zoom" style="height:3px;"></div>
                  <div class="bg-light col rounded" style="height:3px"></div>
                </div>
                <p class="card-text justify1">Blood Donation Society SIESCOMS is Non Government, Non Political Volunteer Society and our Motto is to Seek pleasure of Almighty Allah by Saving Human lives via facilitating blood transfusion.

                  Through our mobile app and website, we provide blood donations across Pakistan with few tabs on finger tips. We try our level best to meet 100% blood requirements voluntarily throughout Pakistan. We have a database of volunteers across the country willing to donate blood who can be reached through this app and our website.

                  For this purpose, we also conduct different seminars and motivational sessions in colleges, universities and local communities. We also create awareness among youth of the country.

                  We aim at ensuring access to safe and sufficient supply of blood and creating acceptability & accessibility in local communities for voluntarily donation of blood.
                </p>
                <!-- <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p> -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- donate section -->
			<div class="container-fluid red-background">
				<div class="row">
					<div class="col-md-12">
						<h1 class="text-center"  style="color: black; font-weight: 700;padding: 10px 0 0 0;">Become a Life Saver!</h1>
						<hr class="white-bar">
						 <!-- <p class="text-center pera-text">
							We are a group of exceptional programmers; our aim is to promote education. If you are a student, then contact us to secure your future. We deliver free international standard video lectures and content. We are also providing services in Web & Software Development.

							We are a group of exceptional programmers; our aim is to promote education. If you are a student, then contact us to secure your future. We deliver free international standard video lectures and content. We are also providing services in Web & Software Development.
						</p> -->
						<!-- <a href="#" class="btn btn-default btn-lg text-center center-aligned" >Become a Life Saver!</a>  -->
					</div>
				</div>
			</div>
			<!-- end doante section -->
			

			<div class="container py-4">
				<div class="row">
    				<div class="col">
    					<div class="card">
     						<h3 class="text-center red">Our Vision</h3>
								<img src="/reddrop/pictures/t3.png" alt="Our Vission" class="img img-responsive mx-auto" width="120" height="120">
								<p class="text-center aim_style">
									We are a group of exceptional programmers; our aim is to promote education. If you are a student, then contact us to secure your future. We deliver free international standard video lectures and content. We are also providing services in Web & Software Development.
								</p>
						</div>
    				</div>
    				
    				<div class="col">
    					<div class="card">
      							<h3 class="text-center red">Our Goal</h3>
								<img src="/reddrop/pictures/t1.png" alt="Our Vission" class="img img-responsive mx-auto " width="120" height="120">
								<p class="text-center aim_style">
									We are a group of exceptional programmers; our aim is to promote education. If you are a student, then contact us to secure your future. We deliver free international standard video lectures and content. We are also providing services in Web & Software Development.
								</p>
						</div>
    				</div>
    			
    				<div class="col">
    					<div class="card">
      						<h3 class="text-center red">Our Mission</h3>
								<img src="/reddrop/pictures/t2.png" alt="Our Vission" class="img img-responsive mx-auto" width="120" height="120">
								<p class="text-center aim_style">
									We are a group of exceptional programmers; our aim is to promote education. If you are a student, then contact us to secure your future. We deliver free international standard video lectures and content. We are also providing services in Web & Software Development.
								</p>
							</div>
   			 		</div>
 			</div>
 		</div>

			<!-- end aboutus section -->




  <?php
  include "partials/Footer.php";
  ?>
  <!-- Optional JavaScript; choose one of the two! -->

  <!-- Option 1: Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>

  <!-- Option 2: Separate Popper and Bootstrap JS -->
  <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js" integrity="sha384-SR1sx49pcuLnqZUnnPwx6FCym0wLsk5JZuNx2bPPENzswTNFaQU1RDvt3wT4gWFG" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js" integrity="sha384-j0CNLUeiqtyaRmlzUHCPZ+Gy5fQu0dQ6eZ/xAww941Ai1SxSY+0EQqNXNE6DZiVc" crossorigin="anonymous"></script>
    -->
  <script src="/reddrop/js/typed1.js"></script>
  <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
  <script>
    var typed = new Typed('.box', {
      strings: ['It does not affect your health', 'It does not affect your wealth', 'Twice a year every year', 'At least once a year'],
      typeSpeed: 40,
      backspaceSpeed: 20,
      backspaceDelay: 80,
      repeatDelay: 10,
      repeat: true,
      autoStart: true,
      startDelay: 10,
      loop: true,
    });
    AOS.init({
      duration: 3000,
      once: true,
    });
  </script>
</body>

</html>