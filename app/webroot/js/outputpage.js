$(function(){
	function pdfPage(){
		var form = $('#nextresult');
		form.append("<input type='hidden' name='pdf' value='chin.geoff@gmail.com'/>");
		form.submit();

		$('#nextresult input[name=pdf]').remove();
	}

	function emailPage(){
		var form = $('#nextresult');
		form.append("<input type='hidden' name='email' value='1'/>");
		form.submit();

		$('#nextresult input[name=email]').remove();
	}
    $('body').on('click', '.pdf-btn',pdfPage);
    $('body').on('click', '.email-btn',emailPage);
});