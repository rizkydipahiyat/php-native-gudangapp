let baseUrl = "http://localhost/gudangapp";
let tableStock;
$(function () {
	tableStock = $("#itemStocks").DataTable({
		processing: true,
		autoWidth: false,
		ajax: `${baseUrl}/stock/getStocks`,
		dom: "Bfrtip",
		buttons: ["copy", "csv", "excel", "pdf", "print"],
		columns: [
			{
				data: "DT_RowIndex",
				searchable: false,
				sortable: false,
			},
			{ data: "name" },
			{ data: "description" },
			{ data: "stock" },
			{
				data: "image",
				render: function (data, type, row) {
					return data;
				},
			},
			{
				data: "action",
				searchable: false,
				sortable: false,
			},
		],
	});
});

function editForm(url) {
	let parts = url.split("/");
	let id = parts[parts.length - 1];
	console.log(id);
	$("#formModal").modal("show");
	$("#formModal .modal-title").text("Edit Stock");
	$("button[type=submit]").text("Update");
	$("#formModal form")[0].reset();
	$("#formModal form").attr("action", url);
	$("#formModal [name=_method]").val("put");
	$("#formModal [name=name]").focus();
	$.get(`${baseUrl}/stock/edit/${id}`)
		.done((response) => {
			$("#formModal [name=name]").val(response.name);
			$("#formModal [name=description]").val(response.description);
			$("#formModal [name=stock]").val(response.stock);
		})
		.fail((errors) => {
			alert("Data gagal ditampilkan");
			return;
		});
}

function deleteData(url) {
	if (confirm("Yakin ingin menghapus data ini?")) {
		let csrfToken = document
			.querySelector('meta[name="csrf-token"]')
			.getAttribute("content");
		$.post(url, {
			_token: csrfToken,
			_method: "delete",
		})
			.done((response) => {
				tableStock.ajax.reload();
			})
			.fail((errors) => {
				alert("Gagal menghapus data!");
				return;
			});
	}
}
