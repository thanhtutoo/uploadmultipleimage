<?php 
	$cn = mysql_connect('localhost','root','');
	if($cn)
	{
		mysql_select_db('mutiplephp',$cn);
	}

	if(isset($_GET['did']))
	{
		$id = $_GET['did'];
		$part = "img/";
		$img = mysql_query("SELECT * FROM tbproduct_detail WHERE p_id = '$id'");
		while($r = mysql_fetch_object($img))
		{
			$old = $r->profile;
			unlink($part.$old);

		}
		mysql_query("DELETE FROM tbproduct_detail WHERE p_id = '$id'");
		mysql_query("DELETE FROM tbproduct WHERE id = '$id'");
	}

	
	if(isset($_POST['upload']))
	{
		$product_name = $_POST['product_name'];

		mysql_query("INSERT INTO tbproduct(product_name) VALUES('$product_name')");
		$id = mysql_insert_id();
		if($id > 0)
		{
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
   						<input type="text" name="product_name" class="form-control">
   					</td>
   						
   				</tr>
   				<tr>
   					<td>
   						<label>Profile</label>
   						<input type="file" name="file[]" multiple class="form-control">   					
   						</div>
   					</td> 

   				</tr>
   			 <input type="submit" value="upload" class="btn btn-primary" name="upload">
   		</table>
   	 </form>

   	 <table class="table table-bordered table-hover">
   	 	 <thead>
   	 	 	 <tr>
   	 	 	 	 <th>ID</th>
   	 	 	 	 <th>PictureName</th>
   	 	 	 	 <th>Profile</th>
   	 	 	 	 <th>Action</th>
   	 	 	 </tr>
   	 	 </thead>
   	 	 <tbody>
   	 	 	 <?php 
   	 	 	 	$pic = mysql_query("SELECT t.id, t.product_name, td.`profile` FROM tbproduct AS t INNER JOIN tbproduct_detail AS td ON t.id = td.p_id GROUP BY t.id");
   	 	 	 	while($row = mysql_fetch_object($pic))
   	 	 	 	{
   	 	 	 		?>
   	 	 	 			<tr>
   	 	 	 			     <td><?= $row->id ?></td>
   	 	 	 			     <td><?= $row->product_name ?></td>
   	 	 	 			     <td><img src="img/<?= $row->profile ?>" style="width:100px;height:100px;"></td>
   	 	 	 			     <td><a href="edit.php?eid=<?= $row->id ?>">Edit</a>|<a onclick="return confirm('Are you sure?')" href="index.php?did=<?= $row->id ?>">Delete</a></td>
   	 	 	 			</tr>
   	 	 	 		<?php 
   	 	 	 	}
   	 	 	 ?>
   	 	 </tbody>
   	 </table>

  </div>
</body>
</html>