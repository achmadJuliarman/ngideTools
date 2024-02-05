<?php 
include_once 'Mobil.php'; 
$mobil = new Mobil();
$dataMobil = $mobil->getDataMobil(); 
$no = 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>CHART JS | rental mobil</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<div class="container">
	<h1>Data Mobil</h1>
	<table class="table table-dark table-sm table-hover mt-4">
	  	<thead>
		    <tr>
		      <th>NO</th>
		      <th>Plat Nomor</th>
		      <th>Merk</th>
		      <th>Aksi</th>
		    </tr>
	  	</thead>
	  	<tbody class="table-group-divider">
	  		<?php foreach($dataMobil as $m) :  ?>
	  		<?php 
	  			$totalRental = [];
	  			$bulanTahun = []; 
	  			$detailRental = $mobil->getMobilByPlat($m['plat']);;

	  			foreach($detailRental as $dr){
	  				array_push($bulanTahun, $dr['bulan_tahun']);
	  				array_push($totalRental, $dr['total_rental']);
	  			}
	  			$bulanTahun = implode(", ", $bulanTahun);
	  			$totalRental = implode(", ", $totalRental);
	  			// var_dump($bulanTahun);
	  			// var_dump($totalRental);
	  		?>
		    <tr>
		      <th scope="row"><?= $no++ ?></th>
		      <td><?= $m['plat'] ?></td>
		      <td><?= $m['merk'] ?></td>
		      <td>
		      	<button type="button" class="btn btn-primary" data-bs-toggle="modal" 
		      	data-bs-target="#modalDetail" id="btn-detail"
		      	data-plat="<?= $m['plat']; ?>" data-merk="<?= $m['merk'] ?>"
		      	data-bulan_tahun="<?= $bulanTahun ?>" data-total_rental="<?= $totalRental ?>">
				Detail
                </button>
		      </td>
		    </tr>
		    <?php endforeach; ?>
	  	</tbody>
	</table>
</div>

<!-- Modal -->

<!-- MODAL DETAIL -->
<div class="modal fade modal-lg" id="modalDetail" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">	
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Detail Rental</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <ul class="list-group">
          <li class="list-group-item" id="plat"></li>
          <li class="list-group-item" id="merk"></li>
        </ul>
        <div class="container">
            <div>
                <canvas id="grafik-detail-rental"></canvas>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- END MODAL DETAIL -->

<script>
	let myChart;
    $(document).on('click', '#btn-detail', function() {
        const plat = $(this).data('plat');
        const merk = $(this).data('merk');
        let totalRental = $(this).data('total_rental');
        let bulanTahun = $(this).data('bulan_tahun');

        console.log(totalRental);
        console.log(bulanTahun);
        $('#modalDetail .modal-title').html(plat);
        $('#modalDetail .modal-body #plat').html('<b>Plat \t: </b>'+plat);
        $('#modalDetail .modal-body #merk').html('<b>Merk \t: </b>'+merk);

        // validasi jka data 1,2 itu dianggap string maka convert jadi number
        if(typeof totalRental === 'string'){
            totalRental = totalRental.split(', ').map(Number);
        }else{
        	totalRental = [totalRental];
        }
        console.log(totalRental);

        // convert string bulan dan tahun menjadi array
        if(bulanTahun.search(',') != -1){
        	bulanTahun = bulanTahun.split(', ');
        }else{
        	bulanTahun = [String(bulanTahun)];
        }
        console.log(bulanTahun);
        const ctx = document.getElementById('grafik-detail-rental');
		// cek apakah canvasnya udah ada kang        
        if(myChart){
        	// kalau ada hancurin dulu kang
        	myChart.destroy();
        	myChart = new Chart(ctx, {
			    type: 'bar',
			    data: {
			      labels: bulanTahun,
			      datasets: [{
			        label: '# of Votes',
			        data: totalRental,
			        borderWidth: 1,
			        backgroundColor: [
                        'rgba(255, 159, 64, 0.8)',
					    'rgba(255, 99, 132, 0.8)',
                        'rgba(255, 205, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(153, 102, 255, 0.8)',
                        'rgba(201, 203, 207, 0.8)'
                    ],
			      }]
			    },
			    options: {
			      scales: {
			        y: {
			          beginAtZero: true
			        }
			      }
			    }
			});
        }else{
        	myChart = new Chart(ctx, {
			    type: 'bar',
			    data: {
			      labels: bulanTahun,
			      datasets: [{
			        label: '# of Votes',
			        data: totalRental,
			        borderWidth: 1,
			        backgroundColor: [
                        'rgba(255, 159, 64, 0.8)',
					    'rgba(255, 99, 132, 0.8)',
                        'rgba(255, 205, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)',
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(153, 102, 255, 0.8)',
                        'rgba(201, 203, 207, 0.8)'
                    ],
			      }]
			    },
			    options: {
			      scales: {
			        y: {
			          beginAtZero: true
			        }
			      }
			    }
			});
        }
		
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
</body>
</html>