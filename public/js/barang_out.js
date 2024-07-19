let tableBarangOut;

$(function () {
	tableBarangOut = $("#itemBarangOut").DataTable({
		processing: true,
		autoWidth: false,
		ajax: `${baseUrl}/barang/getBarangOut`,
		columns: [
			{
				data: "DT_RowIndex",
				searchable: false,
				sortable: false,
			},
			{ data: "name" },
			{ data: "penerima" },
			{ data: "tanggal" },
			{ data: "qty" },
			{ data: "action" },
		],
	});
	const barangOutChart = (chartType) => {
		$.ajax({
			url: `${baseUrl}/home/chartBarangOut`,
			dataType: "json",
			method: "get",
			success: (data) => {
				console.log(data);
				let chartX = [];
				let chartY = [];
				data.map((data) => {
					chartX.push(data.tanggal);
					chartY.push(data.qty);
				});
				const chartData = {
					labels: chartX,
					datasets: [
						{
							label: "Barang Keluar",
							data: chartY,
							backgroundColor: [
								"rgba(255, 99, 132, 0.2)",
								"rgba(255, 159, 64, 0.2)",
								"rgba(255, 205, 86, 0.2)",
								"rgba(75, 192, 192, 0.2)",
								"rgba(54, 162, 235, 0.2)",
								"rgba(153, 102, 255, 0.2)",
								"rgba(201, 203, 207, 0.2)",
							],
							borderColor: [
								"rgb(255, 99, 132)",
								"rgb(255, 159, 64)",
								"rgb(255, 205, 86)",
								"rgb(75, 192, 192)",
								"rgb(54, 162, 235)",
								"rgb(153, 102, 255)",
								"rgb(201, 203, 207)",
							],
							borderWidth: 4,
						},
					],
				};
				const ctx = document.getElementById("chartBarangKeluar");
				const config = {
					type: chartType,
					data: chartData,
				};
				switch (chartType) {
					case "pie":
						const pieColor = [
							"salmon",
							"red",
							"skyblue",
							"orange",
							"gold",
							"pink",
						];
						chartData.datasets[0].backgroundColor = pieColor;
						chartData.datasets[0].borderColor = pieColor;
						break;
					case "bar":
						chartData.datasets[0].backgroundColor = ["skyblue"];
						chartData.datasets[0].borderColor = ["skyblue"];
						break;
					default:
						config.options = {
							scales: {
								y: {
									beginAtZero: true,
								},
							},
						};
				}
				const chart = new Chart(ctx, config);
			},
		});
	};
	barangOutChart("bar");
});

function editFormBarangOut(url) {
	let parts = url.split("/");
	let id = parts[parts.length - 1];
	$("#formModalBarangOut").modal("show");
	$("#formModalBarangOut .modal-title").text("Edit Barang Out");
	$("button[type=submit]").text("Update");
	$("#formModalBarangOut form")[0].reset();
	$("#formModalBarangOut form").attr("action", url);
	$("#formModalBarangOut [name=_method]").val("put");
	$("#formModalBarangOut [name=penerima]").focus();
	$.get(`${baseUrl}/barang/editBarangOut/${id}`)
		.done((response) => {
			console.log(response);
			$("#formModalBarangOut [name=id_stock]").val(response.id_stock);
			$("#formModalBarangOut [name=penerima]").val(response.penerima);
			$("#formModalBarangOut [name=qty]").val(response.qty);
		})
		.fail((errors) => {
			alert("Data gagal ditampilkan");
			return;
		});
}

function deleteDataBarangOut(url) {
	if (confirm("Yakin ingin menghapus data ini?")) {
		let csrfToken = document
			.querySelector('meta[name="csrf-token"]')
			.getAttribute("content");
		$.post(url, {
			_token: csrfToken,
			_method: "delete",
		})
			.done((response) => {
				tableBarangIn.ajax.reload();
			})
			.fail((errors) => {
				alert("Gagal menghapus data!");
				return;
			});
	}
}
