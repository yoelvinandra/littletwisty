
<script>
    let arrGroupLokasi = ['NONE','ALL','MARKETPLACE','KONSINYASI'];
    // $(".SALINDETAILBARANG").hide();
    
    var format_uang   = $.fn.dataTable.render.number(',', '.', 2, '');
	var format_number = $.fn.dataTable.render.number(',', '.', 0, '');
	
	$(".sidebar-toggle").trigger('click');
	
    //Flat blue color scheme for iCheck
    $('input[type="checkbox"].flat-blue, input[type="radio"].flat-blue').iCheck({
        checkboxClass: 'icheckbox_flat-blue',
        radioClass   : 'iradio_flat-blue'
    })  
	//UNTUK EMAIL
	$(function () {
		//Add text editor
		$("#compose-textarea").wysihtml5();
	});
	
	/* SIDEBAR MENU OPEN */
	$('.treeview').on('click', function(event) {
        event.stopPropagation(); // Stop the event from bubbling up
    });
    /* SIDEBAR MENU OPEN */
	
    function dateNowFormatExcel(){
        var now = new Date();
    
        var year = now.getFullYear();         // Get the full year (e.g., 2024)
        var month = String(now.getMonth() + 1).padStart(2, '0');  // Get the month (0-based index, so add 1), and pad to 2 digits
        var day = String(now.getDate()).padStart(2, '0');        // Get the day and pad to 2 digits
        var hours = String(now.getHours()).padStart(2, '0');     // Get the hours and pad to 2 digits
        var minutes = String(now.getMinutes()).padStart(2, '0'); // Get the minutes and pad to 2 digits
        var seconds = String(now.getSeconds()).padStart(2, '0'); // Get the seconds and pad to 2 digits
        
        // Combine into the Ymd_his format
        var formattedDate = `${year}${month}${day}_${hours}${minutes}${seconds}`;
        return formattedDate;
    }
	
	$(".menu-header a").click(function(){
		
		//CEK APAKAH CONTENT NYA LAPORAN ATAU TIDAK
		if($("#mySidenav").html() != null)
		{
			$(".content-wrapper").html("");
		}
	});
	
</script>