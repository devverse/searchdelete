$(function(){
	function pdfPage(){
		var form = $('#nextresult');
		form.append("<input type='hidden' name='pdf' value='chin.geoff@gmail.com'/>");
		form.submit();

		$('#nextresult input[name=pdf]').remove();
	}

	function toggleSetEmail(){
		$('.email-box').toggle();
	}

	function emailPage(){
		var zeemail = $('.email-input').val();
		if(ValidateEmail(zeemail))
		{
			var form = $('#nextresult');
			form.append("<input type='hidden' name='email' value='"+zeemail+"'/>");
			form.submit();
		}
		else
		{
			alert("You have entered an invalid email address!");
		}

		$('#nextresult input[name=email]').remove();
	}

	function ValidateEmail(mail) 
	{
		if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail))
			return (true)
		else
			return (false)
	}
    $('body').on('click', '.pdf-btn',pdfPage);
    $('body').on('click', '.email-btn',toggleSetEmail);
    $('body').on('click', '.email-page',emailPage);
});