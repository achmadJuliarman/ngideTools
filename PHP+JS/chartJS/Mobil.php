<?php 
include_once 'Database.php';

class Mobil{
	function getDataMobil(){
		$sql = mysqli_query(Database::konek(), 'SELECT * FROM mobil;');
		$mobil = mysqli_fetch_all($sql, MYSQLI_ASSOC);
		return $mobil;
	}

	function getMobilByPlat($plat){
		$sql = mysqli_query(Database::konek(), 
			"SELECT
			CONCAT(MONTHNAME(tanggal_rental),' ',YEAR(tanggal_rental)) AS bulan_tahun,
			COUNT(*) AS total_rental
			FROM rental WHERE plat = '$plat'
			GROUP BY bulan_tahun");

		$data = mysqli_fetch_all($sql, MYSQLI_ASSOC);
		return $data;
	}
}


 ?>