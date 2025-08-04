

<div class="easyui-layout" style="width:330px;height:100%; font-weight:bold;" fit="true">
	<div data-options="region:'north'" style="height:35px; padding-left:5px; background-color:white;">
		<label class="font-header-menu"> <?=$menu?></label>
	</div>
	<div  style="background:#e0f7fa;" data-options="region:'west',split:true,hideCollapsedContent:false,collapsed:false" title="Filter">
		<form method='post' target="LaporanPerusahaan"  action='<?=base_url()?>Master/Laporan/LaporanPerusahaan/laporan' id="form_input">
		<table style="border-bottom:1px #000" id="label_laporan">
			<!-- FILTER LAPORAN -->
			<tr>
				<td align="right" id="label_laporan">Perusahaan </td>
				<td><input id="txt_perusahaan" name="txt_perusahaan[]" style="width:220px"/></td>
			</tr>
		</table>
		<br>
		<fieldset>
		<legend>Status</legend>
		<table width="100%">
			<!-- FILTER STATUS -->
			<tr>
				<td align="center" >
							<label  id="label_laporan"><input onchange="filter_data()" type="radio" value="" name="rbStatus" checked>Semua</label>		
				</td>
				<td align="center" >
							<label  id="label_laporan"><input onchange="filter_data()" type="radio" value="AKTIF" name="rbStatus">Aktif</label>		
				</td>
				<td align="center" >
						<label  id="label_laporan"><input onchange="filter_data()" type="radio" value="TIDAK" name="rbStatus"> Tidak</label>
				</td>
			</tr>
		</table>
		</fieldset>
		<!-- NAMA LAPORAN -->
		<input type="hidden" name="excel" id="excel">
		<input type="hidden" name="file_name" id="file_name" value="LaporanPerusahaan">
		</form>
		<br>
		<!-- PRINT LAPORAN -->
		<div align="right">
			<a id="btn_print" href="#" class="easyui-linkbutton" data-options="iconCls:'icon-print', plain:false">Tampilkan Data</a>
			&nbsp;
			<a id="btn_export_excel" href="#" class="easyui-linkbutton" data-options="iconCls:'icon-excel', plain:false">Export</a>
			&nbsp;
		</div>
	</div>
	<div data-options="region:'center'," >
		<div id="tab_laporan" class="easyui-tabs" style="width:100%;height:100%;">
			
		</div>
	</div>
</div>	
	
<script>
var counter = 0;
$(document).ready(function(){
	browse_data_perusahaan('#txt_perusahaan');
});

// PRINT LAPORAN
$("#btn_export_excel").click(function(){
	$('#excel').val('ya');
	$('#form_input').attr('target','_blank');
	$('#form_input').submit();	
	return false;
});

$("#btn_print").click(function(){
	$('#excel').val('tidak');
	counter++
	var tab_title = $('#file_name').val();
	var tab_name = tab_title+counter;
	$('#form_input').attr('target',tab_name);
	
	$('#tab_laporan').tabs('add',{
		title   : tab_title,
		content : '<iframe id="'+tab_name+'" name="'+tab_name+'" width="99%" height="99%" src="#"></iframe>',
		closable: true,
		tools:[{
			iconCls:'icon-print',
			handler:function(){
				window.frames[tab_name].print();
			}
		},{
			iconCls:'icon-mini-refresh',
			handler:function(){	 
				window.frames[tab_name].location.reload();
			}
		}]	
	})
	
	$('#form_input').submit();
	return false;
	
});

function browse_data_perusahaan(id) {
	$(id).combogrid({
		panelWidth    : 380,
		url           : base_url+'Master/Data/Perusahaan/ComboGrid',
		idField       : 'KODE',
		textField     : 'NAMA',
		mode          : 'local',
		sortName      : 'nama',
		sortOrder     : 'asc',
		multiple      : true,
		selectFirstRow: true,
		rowStyler     : function(index,row){
			if (row.STATUS == 0){
				return 'background-color:#A8AEA6';
			}
		},
		columns:[[
			{field:'ck',checkbox:true},
			{field:'KODE',title:'Kode',width:80, sortable:true},
			{field:'NAMA',title:'Nama',width:240, sortable:true},
		]]
	});
}

</script>