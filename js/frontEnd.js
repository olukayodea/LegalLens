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
			getbill(val, users);
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
			getbill(package, val);
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
				document.getElementById('payment_type').setAttribute("disabled", "disabled");
				document.getElementById('payment_frequency').setAttribute("disabled", "disabled");
				document.getElementById("showCard").style.display = "none";
			} else {
				document.getElementById('payment_type').removeAttribute("disabled");
				document.getElementById('payment_frequency').removeAttribute("disabled");
				document.getElementById("showCard").style.display = "none";
			}
			openCard(document.getElementById('payment_frequency').value, myJson[3]);
		} else {
			alert("An error occured, please try again later");
		}
	})
	.fail(function(data) {
	});
}

function getPayment(val) {
	if (val == "Online") {
		document.getElementById('payment_frequency').removeAttribute("disabled");
		openCard(document.getElementById('payment_frequency').value, document.getElementById('total').value);
	} else {
		document.getElementById('payment_frequency').setAttribute("disabled", "disabled");
		openCard(document.getElementById('payment_frequency').value, document.getElementById('total').value);
		document.getElementById("showCard").style.display = "none";
	}
}

function openCard(val, total=document.getElementById('total').value) {
	if ((val == "Renew") && (total > 0)) {
		document.getElementById("showCard").style.display = "block";
		document.getElementById("cardno").setAttribute("required", "required");
		document.getElementById("mm").setAttribute("required", "required");
		document.getElementById("yy").setAttribute("required", "required");
		document.getElementById("billingcity").setAttribute("required", "required");
		document.getElementById("billingstate").setAttribute("required", "required");
		document.getElementById("billingcountry").setAttribute("required", "required");
	} else {
		document.getElementById("showCard").style.display = "none";
		document.getElementById("cardno").removeAttribute("required");
		document.getElementById("mm").removeAttribute("required");
		document.getElementById("yy").removeAttribute("required");
		document.getElementById("billingcity").removeAttribute("required");
		document.getElementById("billingstate").removeAttribute("required");
		document.getElementById("billingcountry").removeAttribute("required");
	}
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

function displayCardType(val) {
	var logo = GetCardType(val);
	if (logo == "Visa") {
		document.getElementById("cardLogo").innerHTML = '<i class="fab fa-cc-visa fa-2x"></i>';
	} else if (logo == "Mastercard") {
		document.getElementById("cardLogo").innerHTML = '<i class="fab fa-cc-mastercard fa-2x"></i>';
	} else if (logo == "AMEX") {
		document.getElementById("cardLogo").innerHTML = '<i class="fab fa-cc-amex fa-2x"></i>';
	} else if (logo == "Discover") {
		document.getElementById("cardLogo").innerHTML = '<i class="fab fa-cc-discover fa-2x"></i>';
	} else if (logo == "Diners") {
		document.getElementById("cardLogo").innerHTML = '<i class="fab fa-cc-diners-club fa-2x"></i>';
	} else if (logo == "Diners - Carte Blanche") {
		document.getElementById("cardLogo").innerHTML = '<i class="fab fa-cc-diners-club fa-2x"></i>';
	} else if (logo == "JCB") {
		document.getElementById("cardLogo").innerHTML = '<i class="fab fa-cc-jcb fa-2x"></i>';
	} else if (logo == "Visa Electron") {
		document.getElementById("cardLogo").innerHTML = '<i class="fab fa-cc-visa fa-2x"></i>';
	} else {
		document.getElementById("cardLogo").innerHTML = '<i class="fab fa-cc-credit-card fa-2x"></i>';
	}
}

function GetCardType(number) {
    // visa
	var re = new RegExp("^4");
    if (number.match(re) != null)
        return "Visa";

    // Mastercard 
	// Updated for Mastercard 2017 BINs expansion
    re = new RegExp("^(5[1-5]|222[1-9]|22[3-9]|2[3-6]|27[01]|2720)[0-9]{0,}$");
    if (number.match(re) != null)
        return "Mastercard";

    // AMEX
    re = new RegExp("^3[47]");
    if (number.match(re) != null)
        return "AMEX";

    // Discover
    re = new RegExp("^(6011|622(12[6-9]|1[3-9][0-9]|[2-8][0-9]{2}|9[0-1][0-9]|92[0-5]|64[4-9])|65)");
    if (number.match(re) != null)
        return "Discover";

    // Diners
    re = new RegExp("^36");
    if (number.match(re) != null)
        return "Diners";

    // Diners - Carte Blanche
    re = new RegExp("^30[0-5]");
    if (number.match(re) != null)
        return "Diners - Carte Blanche";

    // JCB
    re = new RegExp("^35(2[89]|[3-8][0-9])");
    if (number.match(re) != null)
        return "JCB";

    // Visa Electron
    re = new RegExp("^(4026|417500|4508|4844|491(3|7))");
    if (number.match(re) != null)
        return "Visa Electron";

    return "";
}

function monthCheck() {
	var len = document.getElementById('mm').value;
	var lenght = len.length;

	if (lenght >= 2) {
		document.getElementById('yy').focus();
	}
}

function yearCheck() {
	var len = document.getElementById('yy').value;
	var lenght = len.length;

	if (lenght < 1) {
		document.getElementById('mm').focus();
	}
}

$("#cardno").on("keydown", function(e) {
    var cursor = this.selectionStart;
    if (this.selectionEnd != cursor) return;
    if (e.which == 46) {
        if (this.value[cursor] == " ") this.selectionStart++;
    } else if (e.which == 8) {
        if (cursor && this.value[cursor - 1] == " ") this.selectionEnd--;
    }
}).on("input", function() {
    var value = this.value;
    var cursor = this.selectionStart;
    var matches = value.substring(0, cursor).match(/[^0-9]/g);
    if (matches) cursor -= matches.length;
    value = value.replace(/[^0-9]/g, "").substring(0, 16);
    var formatted = "";
    for (var i=0, n=value.length; i<n; i++) {
        if (i && i % 4 == 0) {
            if (formatted.length <= cursor) cursor++;
            formatted += " ";
        }
        formatted += value[i];
    }
    if (formatted == this.value) return;
    this.value = formatted;
    this.selectionEnd = cursor;
});