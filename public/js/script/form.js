$(document).ready(function () {
	const BASEURL = window.location.href;

	$(".tampilModalTambah").click(function () {
		const url = $(this).data("url");

		if ($("#modal").hasClass("edit")) {
			$("#modal").removeClass("edit");
			document.querySelector("#modal form").reset();
		}
		$("#modalLabel").html("Tambah Data");
		$("button[type=submit]").html("Tambah Data");
		$(".modal-body form").attr("action", `${url}`);

		$(".modal-body .tambah").show();
		$(".modal-body .edit").hide();
	});

	$(".tampilModalUbah").click(function () {
		const url = $(this).data("url");
		$("#modal").addClass("edit");
		$("#modalLabel").html("Edit Data");
		$(".modal-footer button[type=submit]").html("Ubah Data");
		$(".modal-body form").attr("action", `${url}`);

		$(".modal-body .tambah").hide();
		$(".modal-body .edit").show();

		const data_id = $(this).data("id");

		$.ajax({
			url: `${BASEURL}/getUbahData`,
			data: { id: data_id },
			method: "post",
			dataType: "json",
			success: function (data) {
				for (key of Object.keys(data)) {
					// $(`.editForm #${key}`).val(data[key]);
					// console.log(`$(.editForm #${key}).val(${data[key]});`);
					// console.log(`$(.editForm #${key}).value = ${data[key]};`);
					// document.querySelectorAll(`#${key}`).value = data[key];
					// document.querySelector(`.editForm #${key}`).value = data[key];
					// document.querySelectorAll(`#${key}`)[1].setAttribute("value", data[key]);
					document.querySelectorAll(`#${key}`)[1].value = data[key];
				}
			},
		});
	});

	$(".batal").click(function () {
		document.querySelector("#modal form").reset();
	});
});
