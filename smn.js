document.addEventListener("DOMContentLoaded", function(event){
	const users = document.querySelector("#users");
	const logout = document.querySelector("#logout");
	let main = document.querySelector("#main");
	
	

	$('#new_contact').on('click',function(e){
		e.preventDefault();
		let url = "new_contact.php";
		console.log("Clicked!: "+ url);
		
		$.ajax(url)
			.done(function(result){
					main.innerHTML =""; 
					main.innerHTML = result;
				}).fail(function(result){main.innerHTML ="";
								 main.innerHTML ='<p class="error"> Couldnt get data</p>';
								 console.log("fetch error");
								});	
	});
	
	$('#home').on('click',function(e){
		e.preventDefault();
		let url = "view_contacts.php";
		console.log("Clicked!: "+ url);
		
		$.ajax(url)
			.done(function(result){
					main.innerHTML =""; 
					main.innerHTML = result;
				}).fail(function(result){main.innerHTML ="";
								 main.innerHTML ='<p class="error"> Couldnt get data</p>';
								 console.log("fetch error");
								});	
	});
	
	$('#users').on('click',function(e){
		e.preventDefault();
		let url = "view_users.php";
		console.log("Clicked!: "+ url);
		
		$.ajax(url)
			.done(function(result){
					main.innerHTML =""; 
					main.innerHTML = result;
				}).fail(function(result){main.innerHTML ="";
								 main.innerHTML ='<p class="error"> Couldnt get data</p>';
								 console.log("fetch error");
								});	
	});	
	
	$('#case').on('click',function(e){
		e.preventDefault();
		let url = "view_cases.php";
		console.log("Clicked!: "+ url);
		
		$.ajax(url)
			.done(function(result){
					main.innerHTML =""; 
					main.innerHTML = result;
				}).fail(function(result){main.innerHTML ="";
								 main.innerHTML ='<p class="error"> Couldnt get data</p>';
								 console.log("fetch error");
								});	
	});		
	
	$('#task').on('click',function(e){
		e.preventDefault();
		let url = "view_task.php";
		console.log("Clicked!: "+ url);
		
		$.ajax(url)
			.done(function(result){
					main.innerHTML =""; 
					main.innerHTML = result;
				}).fail(function(result){main.innerHTML ="";
								 main.innerHTML ='<p class="error"> Couldnt get data</p>';
								 console.log("fetch error");
								});	
	});	

	$('#file').on('click',function(e){
		e.preventDefault();
		let url = "upload_file.php";
		console.log("Clicked!: "+ url);
		
		$.ajax(url)
			.done(function(result){
					main.innerHTML =""; 
					main.innerHTML = result;
				}).fail(function(result){main.innerHTML ="";
								 main.innerHTML ='<p class="error"> Couldnt get data</p>';
								 console.log("fetch error");
								});	
	});	

	$('#new_user').on('click',function(e){
		e.preventDefault();
		let url = "new_user.php";
		console.log("Clicked!: "+ url);
		
		$.ajax(url)
			.done(function(result){
					main.innerHTML =""; 
					main.innerHTML = result;
				}).fail(function(result){main.innerHTML ="";
								 main.innerHTML ='<p class="error"> Couldnt get data</p>';
								 console.log("fetch error");
								});	
	});	
});