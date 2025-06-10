<?php
	include '../config.php';
	if(isset($_GET['id'])){
		$id = $_GET['id'];
		$query_search_req_kembali = $conn->query("SELECT * FROM tbl_req_kembali WHERE id='$id'");
		if($query_search_req_kembali->num_rows > 0) {
			$data_req_kembali = $query_search_req_kembali->fetch_assoc();
			$nama_barang = $data_req_kembali['nama_barang'];
			$peminjam = $data_req_kembali['peminjam'];
			$level = $data_req_kembali['level'];
			$jml_barang = $data_req_kembali['jml_barang'];
			$tgl_pinjam = $data_req_kembali['tgl_pinjam'];
			$tgl_kembali = $data_req_kembali['tgl_kembali'];

			$query_search_barang = $conn->query("SELECT * FROM tbl_barang WHERE nama_barang = '$nama_barang'");
			if($query_search_barang->num_rows > 0) {
				$data_search_barang = $query_search_barang->fetch_assoc();
				$stok_barang = $data_search_barang['stok_barang'] + $jml_barang;
				echo $stok_barang;
				$update_stok = $conn->query("UPDATE tbl_barang SET stok_barang = '$stok_barang' WHERE nama_barang = '$nama_barang'");
				if($update_stok){
					if($conn->query("INSERT INTO tbl_transaksi (nama_barang, peminjam, level, jml_barang, tgl_pinjam, tgl_kembali) VALUES ('$nama_barang', '$peminjam', '$level', '$jml_barang', '$tgl_pinjam', '$tgl_kembali')")){
						if($conn->query("DELETE FROM tbl_req_kembali WHERE id='$id'")){
							$konten = "Permintaan Pengembalian Barang Anda Telah di Terima. ".$jml_barang_request." buah ".$nama_barang_request.". Username: ".$peminjam_request;
								
							if($conn->query("INSERT INTO pemberitahuan (username, konten, status) VALUES ('$peminjam', '$konten', 'kembali')")){
								echo "<script>alert('Berhasil Memproses Pengembalian Barang');</script>";
								header('location: barang-dipinjam.php');
							}else{
								echo "Gagal Menambah Pemberitahuan";
							}						
						}else{
							echo "Gagal Hapus tbl_req_kembali";
						}
					}else{
						echo "Gagal insert ke tbl_transaksi";
					}
				}else{
					echo "Gagal Update Stok Barang";
				}
			}else{
				echo "Gagal Mencari barang";
			}
		}else{
			echo "Data Permintaan Pengembalian Barang tidak ditemukan.";
		}
	}
?>
