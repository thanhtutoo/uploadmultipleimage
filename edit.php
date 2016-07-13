<?php 
	$cn = mysql_connect('localhost','root','');
	if($cn)
	{
		mysql_select_db('mutiplephp',$cn);
	}

	if(isset($_GET['eid']))
	{
		$id   =  $_GET['eid'];
		$img  = mysql_fetch_object(mysql_query("SELECT * FROM tbproduct WHERE id = '$id'"));
		$imgs = mysql_query("SELECT * FROM tbproduct_detail WHERE p_id = '$id'");
		
	}

	
	if(isset($_POST['upload']))
	{
		$id   =  $_GET['eid'];
		$product_name = $_POST['product_name'];

		mysql_query("UPDATE tbproduct SET product_name = '$product_name' WHERE id = '$id'");
		
		if(!empty($_FILES['file']['tmp_name'][0]))
		{			
			// delete old image 
            $part = "img/";
			$img = mysql_query("SELECT * FROM tbproduct_detail WHERE p_id = '$id'");
			while($r = mysql_fetch_object($img))
			{
				$old = $r->profile;
				unlink($part.$old);

			}
			mysql_query("DELETE FROM tbproduct_detail WHERE p_id = '$id'");
			// end 

			foreach($_FILES['file']['tmp_name'] as $i => $tmp_name)
			{
				$filename = $_FILES['file']['name'][$i];
				$filetype = $_FILES['file']['type'][$i];
				$filesize = $_FILES['file']['size'][$i];
				$filetmp  = $_FILES['file']['tmp_name'][$i];
				$store    = rand(0,13248575858).$_FILES['file']['name'][$i];

				
				if(move_uploaded_file($filetmp,"img/".$store))
				{
					mysql_query("INSERT INTO tbproduct_detail(p_id,file_name,file_size,file_ext,profile) VALUES('$id','$filename','$filesize','$filetype','$store')");
				}

			}

			

	    }

	    header('location:index.php');

		
	}

	
?>
	
<html>
<head>
	
	<title>Upload Picture</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<script type="text/javascript" src="js/jquery.js"></script>

</head>
<body style="background-color:brown"><br/>
  <div class="container" style="background-color:#eee;">
      <center><h3 style="color:blue">Upload Multiple Picture</h3></center>

      <form action="" method="post" enctype="multipart/form-data">
   		<table class="table">
   				<tr>   					
   					<td>
   						<label>ProductName</label>
   						<input type="text" name="product_name" class="form-control" value="<?= $img->product_name ?>">
   					</td>
   						
   				</tr>
   				<tr>
   					<td>
   						<label>Profile</label>
   						<input type="file" name="file[]" multiple class="form-control">   					
   						<br/>
   						<?php 
   							while($row = mysql_fetch_object($imgs))
   							{
   								?>
   									<img width="200" height="200" src="img/<?= $row->profile ?>">
   								<?php 
   							}
   						?>

   					</td> 

   				</tr>
   			 <input type="submit" value="Update" class="btn btn-primary" name="upload">
   		</table>
   	 </form>

   	 
  </div>
</body>
</html>