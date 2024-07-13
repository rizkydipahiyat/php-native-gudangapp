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
