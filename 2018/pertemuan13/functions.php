<?php 
// koneksi ke database
$conn = mysqli_connect("localhost", "root", "", "phpdasar");


function query($query) {
	global $conn;
	// var_dump($query);die;
	$result = mysqli_query($conn, $query);
	$rows = [];
	while( $row = mysqli_fetch_assoc($result) ) {
		$rows[] = $row;
	}
	return $rows;
}


function tambah($data) {
	global $conn;

	$nrp = htmlspecialchars($data["nrp"]);
	$nama = htmlspecialchars($data["nama"]);
	$email = htmlspecialchars($data["email"]);
	$jurusan = htmlspecialchars($data["jurusan"]);
	//upload image
	$gambar = upload();
	if(!$gambar){
		return false;
	}

	$query = "INSERT INTO mahasiswa
				VALUES
			  (NULL, '$nama', '$nrp', '$email', '$jurusan', '$gambar')
			";
	mysqli_query($conn, $query);

	return mysqli_affected_rows($conn);
}

function upload() {
	$name = $_FILES['gambar']['name'];
	$size = $_FILES['gambar']['size'];
	$error = $_FILES['gambar']['error'];
	$tempName = $_FILES['gambar']['tmp_name']; 
	$extension = strtolower(end(explode('.', $name)));
	$validExtension = ['jpg', 'jpeg', 'png'];
	$newName = uniqid() . ".$extension";
	$file_destination = 'img/' . $newName;


	if($error === 4){
		echo "
			<script>
			alert('Please choose an image to upload')
			</script>
		";
		return false;
	}
	if(!in_array($extension, $validExtension)){
		echo "
		<script>
		alert('Please upload a valid image extension')
		</script>
		";
		return false;
	}
	if($size > 2000000){
		echo "
		<script>
		alert('File size exceeds configured limit')
		</script>
		";
		return false;
	}
	move_uploaded_file($tempName, $file_destination);

	return $newName;
}


function hapus($id) {
	global $conn;
	mysqli_query($conn, "DELETE FROM mahasiswa WHERE id = $id");
	return mysqli_affected_rows($conn);
}


function ubah ($data) {
	global $conn;

	$id = $data["id"];
	$nrp = htmlspecialchars($data["nrp"]);
	$nama = htmlspecialchars($data["nama"]);
	$email = htmlspecialchars($data["email"]);
	$jurusan = htmlspecialchars($data["jurusan"]);
	$oldPicture = $data['oldPic'];
	if($_FILES['gambar']['error'] === 4){
		$gambar = $oldPicture;
	} else {
		$gambar = htmlspecialchars($data["gambar"]);
	}

	$query = "UPDATE mahasiswa SET 
				nama = '$nama',
				nrp = '$nrp',
				email = '$email',
				jurusan = '$jurusan',
				gambar = '$gambar' 
		WHERE id = $id;
	";

	mysqli_query($conn, $query);
	return mysqli_affected_rows($conn);

}

function search ($keyword) {
	$query = "SELECT * FROM mahasiswa WHERE 
				nama LIKE '%$keyword%' OR
				nrp LIKE '%$keyword%' OR
				jurusan LIKE '%$keyword%' OR
				email LIKE '%$keyword%'";
	return query($query);
}















?>