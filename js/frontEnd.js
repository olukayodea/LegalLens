// JavaScript Document
function fetchList(val) {
	if (val != "") {
		$.post( "includes/scripts/sub_fetch_type.php", { val: val })
		.done(function(data) {
			var myJson = JSON.parse(data);
			var sel = document.getElementById('package');
			var fragment = document.createDocumentFragment();
			$('#package') .empty() .append('<option selected value="">Select a Package</option>');
			
			for (var i=0; i < myJson.length; i += 1) {
				var opt = document.createElement('option');
				opt.innerHTML = myJson[i][1];
				opt.value = myJson[i][0];
				fragment.appendChild(opt);
			}
			
			sel.appendChild(fragment);
		})
		.fail(function(data) {
			$('#package') .empty() .append('<option selected value="">Select User Type First</option>');
		});
	} else {
		$('#package') .empty() .append('<option selected value="">Select User Type First</option>');
	}
}

function selectPackage(val) {
	document.getElementById('num_user').removeAttribute("style");
	var users = document.getElementById('num_user').value;
	if (val != "") {
		if ((users == "") || (isNaN(parseInt(users)) == true)) {
			alert("Please enter a valid number of users");
			document.getElementById('num_user').setAttribute("style", "border: 1px solid #FF9F9F;");
			document.getElementById('num_user').focus();
		} else {
			document.getElementById('num_user').removeAttribute("style");
			getbill(val, users)
		}
	}
}

function enterUsers(val) {
	document.getElementById('package').removeAttribute("style");
	var package = document.getElementById('package').value;
	if (val != "") {
		if ((package == "") || (isNaN(parseInt(package)) == true)) {
			alert("Please select a valid package type");
			document.getElementById('package').setAttribute("style", "border: 1px solid #FF9F9F;");
			document.getElementById('package').focus();
		} else {
			document.getElementById('package').removeAttribute("style");
			getbill(package, val)
		}
	}
}

function getbill(val, users) {
	$.post( "includes/scripts/get_bill.php", { val: val, users:users })
	.done(function(data) {
		if (data != 0) {
			var myJson = JSON.parse(data);
			document.getElementById('s_fee').innerHTML = "&#8358; "+formatNumber(myJson[0]);
			document.getElementById('g_total').innerHTML = "&#8358; "+formatNumber(myJson[1]);
			document.getElementById('d_disc').innerHTML = formatNumber(myJson[2])+"%";
			document.getElementById('n_total').innerHTML = "&#8358; "+formatNumber(myJson[3]);
			document.getElementById('total').value = myJson[3];
			if (myJson[3] < 1) {
				document.getElementById('payment_type').setAttribute("readonly", "readonly");
				alert("hide");
			} else {
				document.getElementById('payment_type').removeAttribute("readonly");
				alert("show");
			}
		} else {
			alert("An error occured, please try again later");
		}
	})
	.fail(function(data) {
	});
}

function toggleStatus(val) {
	if (val == "single") {
		document.getElementById('num_user').value = 1;
		document.getElementById('num_user').setAttribute("readonly", "readonly");
	} else if (val == "group") {
		document.getElementById('num_user').value = 1;
		document.getElementById('num_user').removeAttribute("readonly");
	} else if (val == "") {
		document.getElementById('num_user').value = "";
		document.getElementById('num_user').setAttribute("readonly", "readonly");
	}
}

function formatNumber (num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}

function saveSearch(val) {
	if (val != "") {
		$.post( "includes/scripts/search_save.php", { val: val } );
	}
}