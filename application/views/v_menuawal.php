
<?php

$CI =& get_instance();	
$CI->load->database($_SESSION[NAMAPROGRAM]['CONFIG']);
	
?>

    <section class="content" style="background-image:url('<?php echo base_url(); ?>assets/images/siap.jpg');background-repeat: no-repeat; background-position: center; background-size: cover;">
      <!-- /.row -->
    </section>
    <!-- /.content -->
<!-- ./wrapper -->

<script>
$(".content").css("height",(window.screen.height-280 )+"px");
</script>
</body>
</html>
