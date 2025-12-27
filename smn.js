document.addEventListener("DOMContentLoaded", function(event){
	const users = document.querySelector("#users");
	const logout = document.querySelector("#logout");
	let main = document.querySelector("#main");
	const hView = document.querySelector("#homeview");


	const defaultajx = function(loc){
		$.ajax(loc)
			.done(function(result){
					main.innerHTML =""; 
					main.innerHTML = result;
				}).fail(function(result){main.innerHTML ="";
								 main.innerHTML ='<p class="error"> Couldnt get data</p>';
								 console.log("fetch error");
								});	
	}

	$('#home').on('click',function(e){
		e.preventDefault();
		let url = "index.php";
		console.log("Clicked!: "+ url);
		defaultajx(url);
	});
	
	$('#new_contact').on('click',function(e){
		e.preventDefault();
		let url = "new_contact.php";
		console.log("Clicked!: "+ url);
		defaultajx(url);
	});
	
	$('#users').on('click',function(e){
		e.preventDefault();
		let url = "view_users.php";
		console.log("Clicked!: "+ url);
		defaultajx(url);
	});	
	
	$('#case').on('click',function(e){
		e.preventDefault();
		let url = "view_cases.php";
		console.log("Clicked!: "+ url);
		defaultajx(url);	
	});		
	
	$('#task').on('click',function(e){
		e.preventDefault();
		let url = "view_task.php";
		console.log("Clicked!: "+ url);
		defaultajx(url);
	});	

	$('#file').on('click',function(e){
		e.preventDefault();
		let url = "upload_file.php";
		console.log("Clicked!: "+ url);
		defaultajx(url);
	});	

	$('#new_user').on('click',function(e){
		e.preventDefault();
		let url = "new_user.php";
		console.log("Clicked!: "+ url);
		defaultajx(url);
	});
	
	$(document).on('click','.homeView', function(e){
		e.preventDefault();
		let url = $(this).attr("href");
		console.log("Clicked!: "+ url);
		defaultajx(url);
	});	

	$(document).on('click','.btn-primary', function(e){
		e.preventDefault();
		let url = $(this).attr("href");
		console.log("Clicked!: "+ url);
		defaultajx(url);
	});	

	$(document).on('click', '#homeDelete', function(e){
		e.preventDefault();
		let url = $("#homeDelete").attr("href");
		console.log("Clicked!: "+ url);
		defaultajx(url);
	});	

	$(document).on('click', '#homeTask', function(e){
		e.preventDefault();
		let url = $("#homeTask").attr("href");
		console.log("Clicked!: "+ url);
		defaultajx(url);
	});
	
	$(document).on('click', '.fill', function(e){
		e.preventDefault();
		let url = $(this).attr("href");
		console.log("Clicked!: "+ url);
		defaultajx(url);
	});	
	
	
	$(document).on('click', '#button', function(e){
		e.preventDefault();
		let url = $("#button").attr("href");
		console.log("Clicked!: "+ url);
		defaultajx(url);
	});	

	$(document).on('click', '#upldBtn', function(e){
		e.preventDefault();
		let url = $("#upldBtn").attr("href");
		console.log("Clicked!: "+ url);
		defaultajx(url);
	});
	
	$(document).on('click', '#caseBtn', function(e){
		e.preventDefault();
		let url = $("#caseBtn").attr("href");
		console.log("Clicked!: "+ url);
		defaultajx(url);
	});	

	$(document).on('click', '#taskBtn', function(e){
		e.preventDefault();
		let url = $("#taskBtn").attr("href");
		console.log("Clicked!: "+ url);
		defaultajx(url);
	});	
	
	$(document).on('click', '#return_to_contact_from_view_contact', function(e){
		e.preventDefault();
		let url = $("#return_to_contact_from_view_contact").attr("href");
		console.log("Clicked!: "+ url);
		defaultajx(url);
	});	
	
	$(document).on('click', '#addContactFromDash', function(e){
		e.preventDefault();
		let url = $("#addContactFromDash").attr("href");
		console.log("Clicked!: "+ url);
		defaultajx(url);
	});	
});