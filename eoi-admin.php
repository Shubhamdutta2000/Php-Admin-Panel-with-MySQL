<?php
// Start the session
session_start();
if(!isset($_SESSION['user'])){
    $AUTH = false;
}
else{
    $AUTH = true;
}

?>

<?php include 'templates/header.php'; ?>

<?php 

    require 'config/config.php';
    require 'config/db.php';

    // fetch grse order by created_at column in descending order
    $query = 'SELECT * FROM eoitable';

    // get results
    $result = mysqli_query($conn, $query);
    //var_dump($result);

    // multiple ways to fetch data
    // here using mysqli and get it into an associative array
    $grse = mysqli_fetch_all($result, MYSQLI_ASSOC);
    //  var_dump($grse);

    // Check For Submit
    if(isset($_POST['submit'])) {

        // GET data from form
        $unitName = mysqli_real_escape_string($conn, $_POST['unitName']);
        $eoiDetails = mysqli_real_escape_string($conn, $_POST['eoiDetails']);
        $eoiNum = mysqli_real_escape_string($conn, $_POST['eoiNum']);
        $dateOfEoiPub = mysqli_real_escape_string($conn, $_POST['dateOfEoiPub']);
        $closingDateTime = mysqli_real_escape_string($conn, $_POST['closingDateTime']);
        $bidderPreQualification = mysqli_real_escape_string($conn, $_POST['bidderPreQualification']);
        $biddingInstruction = mysqli_real_escape_string($conn, $_POST['biddingInstruction']);

        if(!$unitName && !$eoiDetails && !$eoiNum && !$tenderDetails && !$dateOfEoiPub && !$closingDateTime && !$bidderPreQualification && !$biddingInstruction) {
            echo '<script type="text/JavaScript"> 
                    alert("All fields are required");
            </script>'  ;
        } 
        else{
            if(isset($_FILES['pdf'])) {// check if the image is uploaded or not
                $pdf_name = $_FILES['pdf']['name']; //this will hold usser uploaded pdf name
                $pdf_type = $_FILES['pdf']['type']; //this will hold usser uploaded pdf type
                $tmp_name = $_FILES['pdf']['tmp_name']; //this temp name will help to save or move this im file to our folder where i store the all user uploaded image
    
    
                //now explode the pdf and get the extentions of the file
                $pdf_explode = explode('.', $pdf_name);
                $pdf_ext = end($pdf_explode); //here we get the extension of the pdf file 
    
                $extensions = ['pdf','PDF']; // storing all supported formate of pdf in this array
                if(in_array($pdf_ext, $extensions) == true) {// if the user uploaded file extensions matched with availabe extesion
                    $time = time(); // hold current time in a var
                                    //when a user upload a pdf the current time will added with the file name
                                    //so all pdf file have a unique name
                    //lets now move the user uloaded pdf to our particular folder
                    $new_pdf_name = $time.$pdf_name;
                    if(move_uploaded_file($tmp_name, "PDFs/".$new_pdf_name)){//if user upload pdf move to our folder successfully
                        //now let's insert all the user data to the data base
    
                        $sql2 = "INSERT INTO `eoitable` 
                                (`unitName`, `eoiDetails`, `eoiNum`, `dateOfEoiPub`, `closingDateTime`, `pdfName`,`biddingInstruction`,`bidderPreQualification`) 
                                VALUES ('$unitName', '$eoiDetails', '$eoiNum',  '$dateOfEoiPub', '$closingDateTime', '$new_pdf_name', '$biddingInstruction', '$bidderPreQualification')";
                        $insert_data = mysqli_query($conn, $sql2);
                        if($insert_data){ //if data is inserted
                            echo '<script type="text/JavaScript"> 
                            alert("Tender uploaded successfully");
                                    </script>'  ;
                                    header('Location: '. ROOT_URL_EOI);
                            
                        }
                        else{
                            echo '<script type="text/JavaScript"> 
                            alert("Something went wrong..!");
                                    </script>'  ;
                        }
    
                    }
                    
                }
                else{
                    echo '<script type="text/JavaScript"> 
                    alert("please select a pdf file");
                            </script>'  ;
                }
    
            }
            else{
                echo '<script type="text/JavaScript"> 
                alert("Please select a pdf");
                        </script>'  ;
            }
        }

    }


        
    // Check For Delete
    echo isset($_POST['delete']);
    if(isset($_POST['delete'])){

        // GET data from form
        $delete_id = mysqli_real_escape_string($conn, $_POST['delete_id']);

        $query = "DELETE FROM eoitable WHERE id='$delete_id'";

        // data is successfuly deleted from grse table then redirect to home page
        if(mysqli_query($conn, $query)) {
            echo ROOT_URL;
            header('Location: '. ROOT_URL_EOI . '');
        }else {
            echo "ERROR: ". mysqli_error($conn);
        }
    }

    // $AUTH = false;
    
    if(isset($_POST['user'])){
        $loginCred = mysqli_real_escape_string($conn, $_POST['user_input']);

        $query = "SELECT * FROM cred WHERE userId = '$loginCred'";

        $result = mysqli_query($conn, $query);
        if(mysqli_num_rows($result)){
            $row = mysqli_fetch_assoc($result);
            $_SESSION['user'] = $row['userId'];
            $AUTH = true;
        }
        else{
            echo '<script type="text/JavaScript"> 
            alert("Wrong Credential");
                    </script>'  ;
        }
    }


    // free the result from memory
    mysqli_free_result($result); // expects mysqli result, that is why

    // close connection 
    mysqli_close($conn);
    
?>

    <!-- Form -->
    <?php
  if($AUTH == 1){
    include './templates/navbarEoi.php';
  }
  ?>
    <?php if($AUTH != 1) { ?>
    <div class="login_page">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="card">
                <label for="user">Please Provide Your Passcode</label>
                <input type="text" name="user_input" id="user_input">
                <button class="checking_btn" name="user">Login</button>
            </div>
        </form>
    </div>
    
    <?php } else { ?>
    
    <div class="container bg-white">
        <h1 class=" pt-5 font-weight-normal display-4 text-center">EOI Admin</h1>
        <form class="p-5" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
            <fieldset>
                <div class="form-group">
                    <label for="unitName" class="form-label mt-4">Unit Name</label>
                    <input type="text" name="unitName" class="form-control bg-secondary" id="unitName" placeholder="Unit Name">
                </div>
                <div class="form-group">
                    <label for="eoiDetails" class="form-label mt-4">EOI Details</label>
                    <input type="text" name="eoiDetails" class="form-control bg-secondary" id="eoiDetails" placeholder="EOI Details">
                </div>
                <div class="form-group">
                    <label for="eoiNum" class="form-label mt-4">EOI No.</label>
                    <input type="text" name="eoiNum" class="form-control bg-secondary" id="eoiNum" placeholder="EOI No.">
                </div>
                <div class="form-group">
                    <label for="dateOfEoiPub" class="form-label mt-4">Date Of Publication</label>
                    <input type="date" name="dateOfEoiPub" class="form-control bg-secondary" id="dateOfEoiPub" placeholder="Date Of Publication">
                </div>
                <div class="form-group">
                    <label for="closingDateTime" class="form-label mt-4">Closing Date/Time</label>
                    <input type="datetime-local" name="closingDateTime" class="form-control bg-secondary" id="closingDateTime" placeholder="Closing Date / Time">
                </div>
                <div class="form-group">
                    <label for="emd" class="form-label mt-4">Bidding Instruction</label>
                    <input type="text" name="biddingInstruction" class="form-control bg-secondary" id="emd" placeholder="Bidding Instruction" />
                </div>
                <div class="form-group">
                    <label for="emd" class="form-label mt-4">Bidder's Pre Qualification</label>
                    <input type="text" name="bidderPreQualification" class="form-control bg-secondary" id="emd" placeholder="Bidder's Pre Qualification" />
                </div>
                <div class="form-group">
                    <label for="formFile" class="form-label mt-4">Upload pdf</label>
                    <input class="form-control bg-secondary" type="file" name='pdf' id="formFile" require />
                    <label for="formFile" class="form-label mt-4">Only PDF allowed</label>
                </div>

                <button type="submit" name="submit" class="mt-3 px-5 btn btn-outline-primary">Submit</button>
            </fieldset>
        </form>
    </div>

    <!-- Table -->
    <div class="p-5 m-5">
        <h1 class="text-center pb-5 display-4">EOI Published</h1>
        <table class="table table-hover text-dark " style="border: 1px solid black;">
            <thead style="border: 1px solid black;">
                <tr >
                    <th scope="col" style="border: 1px solid black;">Sl No.</th>
                    <th scope="col" style="border: 1px solid black;">Unit Name</th>
                    <th scope="col" style="border: 1px solid black;">EOI Details</th>
                    <th scope="col" style="border: 1px solid black;">EOI No.</th>
                    <th scope="col" style="border: 1px solid black;">Date Of Publication</th>
                    <th scope="col" style="border: 1px solid black;">Closing Date/Time</th>
                    <th scope="col" style="border: 1px solid black;">PDF</th>
                    <th scope="col" style="border: 1px solid black;">DELETE</th>
                </tr>
            </thead>
            <tbody style="border: 1px solid black;">
                <?php foreach($grse as $post) : ?>
                <tr style="border: 1px solid black;">
                    <th scope="row" style="border: 1px solid black;"><?php echo $post['id']; ?></th>
                    <td style="border: 1px solid black;">
                        <?php echo $post['unitName']; ?>
                    </td>
                    <td style="border: 1px solid black;">
                    <a href="eoi-view.php?id=<?php echo $post['id'] ?>" target="_blank">
                        <?php echo $post['eoiDetails']; ?></td>
                    </a>
                    <td style="border: 1px solid black;"><?php echo $post['eoiNum']; ?></td>
                    <td style="border: 1px solid black;"><?php echo $post['eoiDetails']; ?></td>
                    <td style="border: 1px solid black;"><?php echo $post['eoiNum']; ?></td>
                    <td style="border: 1px solid black;"><?php echo $post['dateOfEoiPub']; ?></td>
                    <td style="border: 1px solid black;"><?php echo $post['closingDateTime']; ?></td>
                    <td style="border: 1px solid black;"><?php echo $post['pdfName']; ?></td>
                    <td style="border: 1px solid black;">
                     <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <input type="hidden" name="delete_id" value="<?php echo $post['id']; ?>" />
                        <input type="submit" name="delete" class="btn btn-outline-danger" value="Delete" />
                     </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php include 'templates/footer.php'; ?>

    <?php } ?>
