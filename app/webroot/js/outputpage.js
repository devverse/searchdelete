$(function(){
	function pdfPage(){
		var form = $('#nextresult');
		form.append("<input type='hidden' name='pdf' value='chin.geoff@gmail.com'/>");
		form.submit();
	}

	function emailPage(){
		var form = $('#nextresult');
		form.append("<input type='hidden' name='email' value='1'/>");
		form.submit();
	}
    $('body').on('click', '.pdf-btn',pdfPage);
    $('body').on('click', '.email-btn',emailPage);
});