$(document).ready(function () {
	$('.label_input').textbox();
	$.extend($.fn.validatebox.defaults.rules, {
		equals: {
			validator: function (value, param) {
				return value == $(param[0]).val();
			},
			message: 'Field do not match.'
		}
	});
	$.extend($.fn.textbox.defaults, {
		fontTransform: 'besar',
	});
	$.extend($.fn.validatebox.defaults, {
		fontTransform: 'besar',
	});
	$('.number').numberbox().numberbox('setValue', 0);
	$('.noDecimal').numberbox({
		precision: 0,
	}).numberbox('setValue', 0);
	$('.date').datebox().datebox('setValue', date_format())
	$.ajaxSetup({
		error: function (msg) {
			$.messager.progress('close');
			$.messager.alert('Error', 'Error While Process', 'error');
		}
	});
	$(".number").add($(".noDecimal")).on('focus', function () {
		$(this).select();
	});
	$(".number").add($(".noDecimal")).on('mouseup', function () {
		$(this).select();
	});
	$(document).on("mouseup", ".numberbox .textbox-text", function (e) {
		$(this).select();
	});
	$('#form-ubah-password').dialog({
		modal: true,
		closable: true,
		buttons: [{
				text: 'Reset',
				iconCls: 'icon-reload',
				handler: function () {
					$('#form-ubah-password').form('clear');
				}
			}, {
				text: 'Update',
				iconCls: 'icon-save',
				handler: function () {
					if ($('#form-ubah-password').form('validate')) {
						$.ajax({
							type: 'POST',
							dataType: 'json',
							url: "data/process/proses_login.php",
							data: $('#form-ubah-password :input').serialize() + '&act=ubah_password',
							cache: false,
							success: function (data) {
								if (data.success) {
									$.messager.alert('Info', 'Change Password is Success<br>Please Re-Login...', 'info');
									$.ajax({
										type: 'POST',
										url: "data/process/proses_logout.php",
										cache: false,
										success: function (msg) {
											window.location = 'index.php';
										}
									});
								} else {
									$.messager.alert('Error', data.errorMsg, 'error');
								}
							}
						});
					}
				}
			}
		],
		onOpen: function () {
			$(this).form('clear');
		},
	}).dialog('close');
});
function ubah_tgl_indo(date) {
	return date;
}
function ubah_tgl_mysql(date) {
	if (date != null) {
		var tahun = date.substr(6, 4);
		var tgl = date.substr(0, 2);
		var bulan = date.substr(3, 2);
		return tahun + "-" + bulan + "-" + tgl;
	}
}
function logout() {
	$.messager.confirm('Question', 'Anda Yakin Akan Keluar Dari Sistem ?', function (r) {
		if (r) {
			$.ajax({
				type: 'POST',
				url: base_url+"Login/logout",
				cache: false,
				success: function (msg) {
					window.location = base_url;
				}
			});
		}
	});
}
function login(first_login) {
	var data = $("#form_login :input").serialize();
	$.ajax({
		type: 'POST',
		dataType: 'json',
		url: base_url+"Login/cekLogin",
		data: data + '&act=first_login',
		cache: false,
		success: function (msg) {
			if (msg.success) {
				$.messager.alert('Info', msg.info);
				if (first_login) {
					window.location = base_url+"Home";
				} else {
					location.reload();
				}
			} else {
				$.messager.alert('Error', msg.errorMsg, 'error');
			}
		}
	});
}
function select_menu(link) {
	window.location = link;
}
var int = self.setInterval("clock()", 1000);
function clock() {
	var time = new Date();
	var sh = time.getHours() + "";
	var sm = time.getMinutes() + "";
	var ss = time.getSeconds() + "";
	$('#label_jam').html((sh.length == 1 ? "0" + sh : sh) + ":" + (sm.length == 1 ? "0" + sm : sm) + ":" + (ss.length == 1 ? "0" + ss : ss));
}
function date_format(date) {
	date = typeof date !== 'undefined' ? date : new Date();
	var y = date.getFullYear();
	var m = date.getMonth() + 1;
	var d = date.getDate();
	return y + '-' + (m < 10 ? ('0' + m) : m) + '-' + (d < 10 ? ('0' + d) : d);
}
function time_format(date) {
	date = typeof date !== 'undefined' ? date : new Date();
	var h = date.getHours();
	var m = date.getMinutes();
	return h + ':' + m;
}
function date_parser(s) {
	if (!s)
		return new Date();
	var ss = (s.split('-'));
	var y = parseInt(ss[0], 10);
	var m = parseInt(ss[1], 10);
	var d = parseInt(ss[2], 10);
	if (!isNaN(y) && !isNaN(m) && !isNaN(d)) {
		return new Date(y, m - 1, d);
	} else {
		return new Date();
	}
}
function format_amount(val, row) {
	if (isNaN(val)) {
		return val;
	} else {
		return accounting.formatNumber(val, decimaldigitamount, ",", ".");
	}
}
function format_qty(val, row) {
	if (isNaN(val)) {
		return val;
	} else {
		return accounting.formatNumber(val, decimaldigitqty, ",", ".");
	}
}
function format_amount_4(val, row) {
	if (isNaN(val)) {
		return val;
	} else {
		return accounting.formatNumber(val, 4, ",", ".");
	}
}
function format_number(val, row) {
	if (!val && isNaN(val)) {
		return val;
	} else {
		return accounting.formatNumber(val, 0, ",");
	}
}
function format_remark(val, row) {
	if (typeof val == 'undefined')
		return '';
	else
		val.replace("\n", "<br>");
}
function format_checked(val) {
	if (val == 1) {
		return '<input type="checkbox" checked="checked" disabled="disabled"/>';
	} else {
		return '<input type="checkbox" disabled="disabled"/>';
	}
}
shortcut = {
	'all_shortcuts': {},
	'add': function (shortcut_combination, callback, opt) {
		var default_options = {
			'type': 'keydown',
			'propagate': false,
			'disable_in_input': false,
			'target': document,
			'keycode': false
		}
		if (!opt)
			opt = default_options;
		else {
			for (var dfo in default_options) {
				if (typeof opt[dfo] == 'undefined')
					opt[dfo] = default_options[dfo];
			}
		}
		var ele = opt.target;
		if (typeof opt.target == 'string')
			ele = document.getElementById(opt.target);
		var ths = this;
		shortcut_combination = shortcut_combination.toLowerCase();
		var func = function (e) {
			e = e || window.event;
			if (opt['disable_in_input']) {
				var element;
				if (e.target)
					element = e.target;
				else if (e.srcElement)
					element = e.srcElement;
				if (element.nodeType == 3)
					element = element.parentNode;
				if (element.tagName == 'INPUT' || element.tagName == 'TEXTAREA')
					return;
			}
			if (e.keyCode)
				code = e.keyCode;
			else if (e.which)
				code = e.which;
			var character = String.fromCharCode(code).toLowerCase();
			if (code == 188)
				character = ",";
			if (code == 190)
				character = ".";
			var keys = shortcut_combination.split("+");
			var kp = 0;
			var shift_nums = {
				"`": "~",
				"1": "!",
				"2": "@",
				"3": "#",
				"4": "$",
				"5": "%",
				"6": "^",
				"7": "&",
				"8": "*",
				"9": "(",
				"0": ")",
				"-": "_",
				"=": "+",
				";": ":",
				"'": "\"",
				",": "<",
				".": ">",
				"/": "?",
				"\\": "|"
			}
			var special_keys = {
				'esc': 27,
				'escape': 27,
				'tab': 9,
				'space': 32,
				'return': 13,
				'enter': 13,
				'backspace': 8,
				'scrolllock': 145,
				'scroll_lock': 145,
				'scroll': 145,
				'capslock': 20,
				'caps_lock': 20,
				'caps': 20,
				'numlock': 144,
				'num_lock': 144,
				'num': 144,
				'pause': 19,
				'break': 19,
				'insert': 45,
				'home': 36,
				'delete': 46,
				'end': 35,
				'pageup': 33,
				'page_up': 33,
				'pu': 33,
				'pagedown': 34,
				'page_down': 34,
				'pd': 34,
				'left': 37,
				'up': 38,
				'right': 39,
				'down': 40,
				'f1': 112,
				'f2': 113,
				'f3': 114,
				'f4': 115,
				'f5': 116,
				'f6': 117,
				'f7': 118,
				'f8': 119,
				'f9': 120,
				'f10': 121,
				'f11': 122,
				'f12': 123
			}
			var modifiers = {
				shift: {
					wanted: false,
					pressed: false
				},
				ctrl: {
					wanted: false,
					pressed: false
				},
				alt: {
					wanted: false,
					pressed: false
				},
				meta: {
					wanted: false,
					pressed: false
				}
			};
			if (e.ctrlKey)
				modifiers.ctrl.pressed = true;
			if (e.shiftKey)
				modifiers.shift.pressed = true;
			if (e.altKey)
				modifiers.alt.pressed = true;
			if (e.metaKey)
				modifiers.meta.pressed = true;
			for (var i = 0; k = keys[i], i < keys.length; i++) {
				if (k == 'ctrl' || k == 'control') {
					kp++;
					modifiers.ctrl.wanted = true;
				} else if (k == 'shift') {
					kp++;
					modifiers.shift.wanted = true;
				} else if (k == 'alt') {
					kp++;
					modifiers.alt.wanted = true;
				} else if (k == 'meta') {
					kp++;
					modifiers.meta.wanted = true;
				} else if (k.length > 1) {
					if (special_keys[k] == code)
						kp++;
				} else if (opt['keycode']) {
					if (opt['keycode'] == code)
						kp++;
				} else {
					if (character == k)
						kp++;
					else {
						if (shift_nums[character] && e.shiftKey) {
							character = shift_nums[character];
							if (character == k)
								kp++;
						}
					}
				}
			}
			if (kp == keys.length && modifiers.ctrl.pressed == modifiers.ctrl.wanted && modifiers.shift.pressed == modifiers.shift.wanted && modifiers.alt.pressed == modifiers.alt.wanted && modifiers.meta.pressed == modifiers.meta.wanted) {
				callback(e);
				if (!opt['propagate']) {
					e.cancelBubble = true;
					e.returnValue = false;
					if (e.stopPropagation) {
						e.stopPropagation();
						e.preventDefault();
					}
					return false;
				}
			}
		}
		this.all_shortcuts[shortcut_combination] = {
			'callback': func,
			'target': ele,
			'event': opt['type']
		};
		if (ele.addEventListener)
			ele.addEventListener(opt['type'], func, false);
		else if (ele.attachEvent)
			ele.attachEvent('on' + opt['type'], func);
		else
			ele['on' + opt['type']] = func;
	},
	'remove': function (shortcut_combination) {
		shortcut_combination = shortcut_combination.toLowerCase();
		var binding = this.all_shortcuts[shortcut_combination];
		delete (this.all_shortcuts[shortcut_combination])
		if (!binding)
			return;
		var type = binding['event'];
		var ele = binding['target'];
		var callback = binding['callback'];
		if (ele.detachEvent)
			ele.detachEvent('on' + type, callback);
		else if (ele.removeEventListener)
			ele.removeEventListener(type, callback, false);
		else
			ele['on' + type] = false;
	}
}
$.extend($.fn.datagrid.methods, {
	getChecked: function (jq) {
		var rr = [];
		var rows = jq.datagrid('getRows');
		jq.datagrid('getPanel').find('div.datagrid-cell-check input:checked').each(function () {
			var index = $(this).parents('tr:first').attr('datagrid-row-index');
			rr.push(rows[index]);
		});
		return rr;
	}
});
function get_index(dg) {
	var row = $(dg).datagrid('getSelected');
	var id = $(dg).datagrid('getRowIndex', row);
	return id;
}
function get_data_user(kodemenu, callback) {
	$.ajax({
		dataType: "json",
		type: 'POST',
		url: "data/process/proses_login.php",
		data: "act=re_login&kodemenu=" + kodemenu + "&" + $("#form_login :input").serialize(),
		cache: false,
		success: function (msg) {
			if (msg.success) {
				callback(msg.data);
			} else {
				$.messager.alert('Error', msg.errorMsg, 'error');
			}
		}
	});
}
function get_status_trans(v_link, v_idtrans, callback) {
	$.ajax({
		dataType: "json",
		type: 'POST',
		url: base_url+v_link+"/getStatusTrans",
		data: {
			idtrans: v_idtrans
		},
		cache: false,
		success: function (msg) {
			callback(msg);
		}
	});
}
function ubah_status_trans(v_kodetrans, v_table, v_field, v_status, callback) {
	$.ajax({
		dataType: "json",
		type: 'POST',
		url: "config/transaction_function.php",
		data: {
			act: 'ubah_status',
			kodetrans: v_kodetrans,
			table: v_table,
			field: v_field,
			status: v_status
		},
		cache: false,
		success: function (msg) {
			callback(msg);
		}
	});
}
function get_combogrid_data(obj_combogrid, field, table) {
	var data = obj_combogrid.combogrid('grid').datagrid('getData').firstRows;
	var data = (typeof data != 'undefined') ? data : obj_combogrid.combogrid('grid').datagrid('getRows');
	var ketemu = false;
	var pjg = data.length;
	for (var i = 0; i < pjg; i++) {
		if (data[i].KODE == field) {
			obj_combogrid.combogrid('grid').datagrid('loadData', [data[i]]);
			obj_combogrid.combogrid('setValue', field);
			ketemu = true;
			return;
		}
	}
	if (!ketemu) {
		$.ajax({
			type: 'POST',
			url: 'config/combogrid.php?table=' + table,
			data: "q=" + field,
			cache: false,
			success: function (msg) {
				obj_combogrid.combogrid('grid').datagrid('loadData', JSON.parse(msg));
				obj_combogrid.combogrid('setValue', field);
				obj_combogrid.combogrid('options').onChange.call();
			}
		});
	}
}
function clear_data(id) {
	$(id).combogrid('grid').datagrid('loadData', []);
	$(id).combogrid('clear');
}
function get_konversi(data, newVal, oldVal) {
	satuan_lama = 0;
	konversi_lama = 1;
	satuan_baru = 0;
	konversi_baru = 1;
	var pjg = data.length;
	for (var i = 0; i < pjg; i++) {
		var sat = data[i].SATUAN;
		var jenis = data[i].JENIS;
		satuan_lama = sat == oldVal ? jenis : satuan_lama;
		satuan_baru = sat == newVal ? jenis : satuan_baru;
	}
	for (var i = 0; i < pjg; i++) {
		var konversi = parseFloat(data[i].KONVERSI);
		var jenis = data[i].JENIS;
		konversi_baru = parseFloat(satuan_baru > satuan_lama ? ((jenis <= satuan_baru && jenis > satuan_lama) ? (konversi_baru * konversi) : konversi_baru) : konversi_baru);
		konversi_lama = parseFloat(satuan_baru < satuan_lama ? ((jenis <= satuan_lama && jenis > satuan_baru) ? (konversi_lama * konversi) : konversi_lama) : konversi_lama);
	}
}
function create_form_login() {
	var html = '<div id="form_login" class="easyui-dialog" title="Login to Authorization" closable="false" style="width:100%;max-width:250px;padding:20px 10px;">';
	html += '<div style="margin-bottom:10px">';
	html += '<input class="label_input" name="txt_user" style="width:100%;height:40px;padding:12px" data-options="prompt:\'User ID\',iconCls:\'icon-man\',iconWidth:38,fontTransform:\'normal\'">';
	html += '</div>';
	html += '<div style="">';
	html += '<input class="label_input" name="txt_pass" type="password" style="width:100%;height:40px;padding:12px" data-options="prompt:\'Password\',iconCls:\'icon-lock\',iconWidth:38,fontTransform:\'normal\'">';
	html += '</div>';
	html += '</div>';
	$('body').prepend(html);
	$('.label_input').textbox();
}
function datagrid_up_and_down(dg) {
	$(dg).datagrid('getPanel').panel('panel').prop('tabindex', 1).bind('keydown', function (e) {
		switch (e.keyCode) {
		case 38:
			rows = $(dg).datagrid('getRows');
			if (!edit_row && rows.length >= 0) {
				index = get_index(dg) - 1;
				if (index >= 0 && index <= (rows.length - 1))
					$(dg).datagrid('selectRow', index);
				else if (index < 0 && rows.length > 0)
					$(dg).datagrid('selectRow', 0);
			}
			break;
		case 40:
			rows = $(dg).datagrid('getRows');
			if (!edit_row && rows.length >= 0) {
				index = get_index(dg) + 1;
				if (index >= 0 && index <= (rows.length - 1))
					$(dg).datagrid('selectRow', index);
				else if (index < 0 && rows.length > 0)
					$(dg).datagrid('selectRow', 0);
			}
			break;
		}
	});
}
function get_editor(dg, index, field) {
	return $(dg).datagrid('getEditor', {
		index: index,
		field: field
	}).target;
}
if ($.fn.numberbox) {
	$.fn.numberbox.defaults.precision = decimaldigitamount;
	$.fn.numberbox.defaults.groupSeparator = ',';
	$.fn.numberbox.defaults.decimalSeparator = '.';
}
if ($.fn.datebox) {
	$.fn.datebox.defaults.formatter = date_format;
	$.fn.datebox.defaults.parser = date_parser;
}
if ($.fn.textbox) {
	$.fn.textbox.defaults.fontTransform = 'besar';
	$.fn.textbox.defaults.inputEvents = {
		blur: function (e) {
			var t = $(e.data.target);
			var opt = t.textbox("options");
			var val = opt.value;
			var opt_tf = opt.fontTransform;
			if (opt_tf == 'besar')
				val = val.toUpperCase();
			else if (opt_tf == 'kecil')
				val = val.toLowerCase();
			t.textbox("setValue", val);
		},
	};
}
if ($.fn.validatebox) {
	$.fn.validatebox.defaults.fontTransform = 'besar';
	$.fn.validatebox.defaults.events = {
		blur: function (e) {
			var t = $(e.data.target);
			var opt = t.validatebox("options");
			var val = t.val();
			var opt_tf = opt.fontTransform;
			if (opt_tf == 'besar')
				val = val.toUpperCase();
			else if (opt_tf == 'kecil')
				val = val.toLowerCase();
			t.val(val);
		},
	};
}
if ($.fn.combogrid) {
	$.fn.combogrid.defaults.selectOnNavigation = false;
}
if ($.messager) {
	$.messager.defaults.ok = 'Ya';
	$.messager.defaults.cancel = 'Tidak';
}
function export_excel(file_name, data) {
	var str = "<form method='post' action='config/export_to_excel.php' style='display:none' id='ReportTableData'>";
	str += "<input type='text' name='file_name' value='" + file_name + "'>";
	str += "<textarea name='tableData'>" + data + "</textarea>";
	str += "</form>";
	$('body').prepend(str);
	$('#ReportTableData').submit().remove();
}

function get_kurs(tanggal, id) {
	var nilaikurs = 1;
	$.ajax({
		async:false,
		dataType: "json",
		type: 'POST',
		url: base_url+"Master/Currency/rate",
		data: {
			tanggal: tanggal,
			idcurrency: id
		},
		cache: false,
		success: function (msg) {
			//callback(msg);
			nilaikurs = msg.kurs;
		}
	});
	
	return nilaikurs;
}
function get_all_kurs(tanggal, callback) {
	$.ajax({
		dataType: "json",
		type: 'POST',
		url: base_url+"Master/Currency/all_rate",
		data: {
			tanggal: tanggal
		},
		cache: false,
		success: function (msg) {
			callback(msg);
		}
	});
}
function ubah_url_combogrid(target, str_url, opt) {
	target.combogrid('grid').datagrid('options').url = str_url;
	if (typeof opt == 'boolean' && opt) {
		target.combogrid('clear');
		target.combogrid('grid').datagrid('load', {
			q: ''
		});
	}
	if (typeof opt == 'object') {
		if (opt.clear)
			target.combogrid('clear');
		if (opt.reload)
			target.combogrid('grid').datagrid('load', {
				q: ''
			});
	}
}
function get_tgl_jatuh_tempo(e_tgljthtempo, tgltrans, selisih) {
	// parsing date
	var d = date_parser(tgltrans);

	// due date
	d.setDate(d.getDate() + parseInt(selisih));

	e_tgljthtempo.datebox('setValue', date_format(d));
}
function get_stok(kodebrg, tgltrans, kodelokasi, kodegudang, callback) {
	$.ajax({
		type: 'POST',
		dataType: 'json',
		url: "config/numberbox.php?table=get_stok",
		data: {
			kodebarang: kodebrg,
			tanggal: tgltrans,
			kodelokasi: kodelokasi,
			kodegudang: kodegudang
		},
		success: function (msg) {
			callback(msg);
		},
		cache: false,
	});
}
function cek_datagrid(dg) {
	var fields = dg.datagrid('getColumnFields', true).concat(dg.datagrid('getColumnFields'));
	var rows = dg.datagrid('getRows');
	var ln = rows.length;
	var rowIndex = 0;
	var simpan = true;
	while (rowIndex < ln) {
		var i = 0;
		while (i < fields.length) {
			var col = dg.datagrid('getColumnOption', fields[i]);
			if (typeof col != 'undefined' && col != 'null') {
				if (typeof col.editor != 'undefined' && col.editor != 'null') {
					if (typeof col.editor.options != 'undefined' && col.editor.options != 'null') {
						if (col.editor.options.required) {
							if (rows[rowIndex][fields[i]] === '') {
								simpan = false;
								$.messager.alert('Error', 'Cek Baris ' + (rowIndex + 1) + ', Kolom ' + col.title + ' Belum Diisi', 'error');
								break;
							}
						}
					}
				}
			}
			i++;
		}
		rowIndex++;
	}
	return simpan;
}
function BuatMark() {
	/*$.getJSON("data/process/get_mark.php", function (data) {
		if (data.TAMPIL_MARK == 0 && data.SELALU_MARK == 0) {
			$('#fm-mark').hide();
			$('#MARK').click(function () {
				return false;
			});
			$('#fm-filter-mark').html('<label id="label_form"><input type="checkbox" value="1" id="cb_mark" name="cb_mark"> M</label>');
			$('#cb_mark').click(function () {
				return false;
			});
			$('.filter-mark').hide();
		} else if (data.TAMPIL_MARK == 1 && data.SELALU_MARK == 0) {
			$('#fm-filter-mark').html('<label id="label_form"><input type="checkbox" value="1" id="cb_mark" name="cb_mark"> M</label>');
		} else if (data.TAMPIL_MARK == 1 && data.SELALU_MARK == 1) {
			$('#MARK').prop('checked', true);
			$('#MARK').click(function () {
				return false;
			});
			$('#fm-filter-mark').html('<label id="label_form"><input type="checkbox" value="1" id="cb_mark" name="cb_mark" checked> M</label>');
			$('#cb_mark').click(function () {
				return false;
			});
		} else {
			$('#fm-mark').hide();
			$('#MARK').prop('checked', true);
			$('#MARK').click(function () {
				return false;
			});
			$('#fm-filter-mark').html('<label id="label_form"><input type="checkbox" value="1" id="cb_mark" name="cb_mark" checked> M</label>');
			$('#cb_mark').click(function () {
				return false;
			});
			$('.filter-mark').hide();
		}
	});*/
}

function cek_format(data){
 data = data.replace(/,/g,'.');
 data = data.replace(/ /g,'');
 return data.match(/^[+0-9.]+$/g) !== null ? data : "error";
}