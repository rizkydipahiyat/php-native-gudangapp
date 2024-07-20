let tableBarangIn;

$(function () {
	tableBarangIn = $("#itemBarangIn").DataTable({
		processing: true,
		autoWidth: false,
		ajax: `${baseUrl}/barang/getBarangIn`,
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
});

function editFormBarangIn(url) {
	let parts = url.split("/");
	let id = parts[parts.length - 1];
	$("#formModalBarangIn").modal("show");
	$("#formModalBarangIn .modal-title").text("Edit Barang In");
	$("button[type=submit]").text("Update");
	$("#formModalBarangIn form")[0].reset();
	$("#formModalBarangIn form").attr("action", url);
	$("#formModalBarangIn [name=_method]").val("put");
	$("#formModalBarangIn [name=penerima]").focus();
	$.get(`${baseUrl}/barang/editBarangIn/${id}`)
		.done((response) => {
			console.log(response);
			$("#formModalBarangIn [name=id_stock]").val(response.id_stock);
			$("#formModalBarangIn [name=penerima]").val(response.penerima);
			$("#formModalBarangIn [name=tanggal]").val(response.tanggal);
			$("#formModalBarangIn [name=qty]").val(response.qty);
		})
		.fail((errors) => {
			alert("Data gagal ditampilkan");
			return;
		});
}

function deleteDataBarangIn(url) {
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

function editPeriode() {
	$("#formModalBarangInDate").modal("show");
}
