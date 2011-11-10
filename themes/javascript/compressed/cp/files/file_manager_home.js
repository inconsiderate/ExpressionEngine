/*!
 * ExpressionEngine - by EllisLab
 *
 * @package		ExpressionEngine
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2003 - 2011, EllisLab, Inc.
 * @license		http://expressionengine.com/user_guide/license.html
 * @link		http://expressionengine.com
 * @since		Version 2.0
 * @filesource
 */

$.ee_filemanager=$.filemanager||{};$(document).ready(function(){$.ee_filemanager.file_uploader();$.ee_filemanager.datatables();$.ee_filemanager.image_overlay();$.ee_filemanager.date_range();$.ee_filemanager.toggle_all();$.ee_filemanager.directory_change();$(".paginationLinks .first").hide();$(".paginationLinks .previous").hide()});
$.ee_filemanager.file_uploader=function(){$.ee_fileuploader({type:"filemanager",load:function(){$.template("filemanager_row",$("#filemanager_row").remove())},open:function(){$.ee_fileuploader.set_directory_id($("#dir_id").val())},after_upload:function(b,a){a.replace==!0&&$(".mainTable tbody tr:has(td:contains("+a.file_id+")):has(td:contains("+a.file_name+"))").remove();$(".mainTable tbody tr:first").clone();a.actions="";$.each(EE.fileuploader.actions,function(c,d){var b=d.replace("[file_id]",a.file_id).replace("[upload_dir]",
a.upload_directory_prefs.id);if(c=="delete")a.action_delete=b;else if(c!="image"||a.is_image)a.actions+=b+"&nbsp;&nbsp;"});if(typeof a.title=="undefined")a.title=a.name;if(a.is_image){var f=$("<a>",{id:"",href:a.upload_directory_prefs.url+a.file_name,title:a.file_name,text:a.title,rel:"#overlay","class":"less_important_link overlay"});a.link=f.wrap("<div>").parent().html()}else a.link=a.title;$(".mainTable tbody").prepend($.tmpl("filemanager_row",a));$("td.dataTables_empty").size()&&$("td.dataTables_empty").parent().remove();
a.replace!=!0&&$("#file_uploader").dialog("option","position","center")},trigger:"#action_nav a.upload_file"})};$.ee_filemanager.directory_change=function(){function b(b){a[b]===void 0&&(b=0);jQuery.each(a[b],function(a,b){$("select#cat_id").empty().append(b)})}var a=EE.file.directoryInfo,f=RegExp("!-!","g");$.each(a,function(b,d){$.each(d,function(d,k){var i=new String;$.each(k,function(a,b){i+='<option value="'+b[0]+'">'+b[1].replace(f,String.fromCharCode(160))+"</option>"});a[b][d]=i})});$("#dir_id").change(function(){b(this.value)})};
$.ee_filemanager.date_range=function(){function b(){if($("#custom_date_start").val()!="yyyy-mm-dd"&&$("#custom_date_end").val()!="yyyy-mm-dd")focus_number=$("#date_range").children().length,$("#date_range").append('<option id="custom_date_option">'+$("#custom_date_start").val()+" to "+$("#custom_date_end").val()+"</option>"),document.getElementById("date_range").options[focus_number].selected=!0,$("#custom_date_picker").slideUp("fast"),oTable.fnDraw()}$("#custom_date_start_span").datepicker({dateFormat:"yy-mm-dd",
prevText:"<<",nextText:">>",onSelect:function(a){$("#custom_date_start").val(a);b()}});$("#custom_date_end_span").datepicker({dateFormat:"yy-mm-dd",prevText:"<<",nextText:">>",onSelect:function(a){$("#custom_date_end").val(a);b()}});$("#custom_date_start, #custom_date_end").focus(function(){$(this).val()=="yyyy-mm-dd"&&$(this).val("")});$("#custom_date_start, #custom_date_end").keypress(function(){$(this).val().length>=9&&b()});$("#date_range").change(function(){$("#date_range").val()=="custom_date"?
($("#custom_date_start").val("yyyy-mm-dd"),$("#custom_date_end").val("yyyy-mm-dd"),$("#custom_date_option").remove(),$("#custom_date_picker").slideDown("fast")):$("#custom_date_picker").hide()})};$.ee_filemanager.toggle_all=function(){$(".toggle_all").toggle(function(){$("input.toggle").each(function(){this.checked=!0})},function(){$("input.toggle").each(function(){this.checked=!1})})};
$.ee_filemanager.image_overlay=function(){$("a.overlay").live("click",function(){$("#overlay").hide().removeData("overlay");$("#overlay .contentWrap img").remove();$("<img />").appendTo("#overlay .contentWrap").load(function(){var b=$(this).clone().appendTo(document.body).show(),a=b.width(),f=b.height(),c=$(window).width()*0.8,d=$(window).height()*0.8;c/=a;d/=f;d=c>d?d:c;b.remove();d<1&&(f*=d,a*=d,$(this).height(f).width(a));$("#overlay").overlay({load:!0,speed:100,top:"center"})}).attr("src",$(this).attr("href"));
return!1});$("#overlay").css("cursor","pointer").click(function(){$(this).fadeOut(100)})};
$.ee_filemanager.datatables=function(){var b,a,f,c,d,m;function k(a,b,d){for(var c=0,f=a.length;c<f;c++)if(a[c].name==b)a[c].value=d}function i(a,b){for(var d=0,c=a.length;d<c;d++)if(a[d].name==b)return a[d].value;return null}a=-1;b=void 0;f=void 0;c=void 0;d=void 0;m=void 0;EE.file.tableColumns==9?(MyCols=[null,null,null,null,null,null,null,{bSortable:!1},{bSortable:!1},{bSortable:!1}],MySortCol=6):(MyCols=[null,null,null,null,null,null,{bSortable:!1},{bSortable:!1},{bSortable:!1}],MySortCol=5);
oTable=$("#file_form .mainTable").dataTable({sPaginationType:"full_numbers",bLengthChange:!1,aaSorting:[[MySortCol,"desc"],[0,"desc"]],bFilter:!1,sWrapper:!1,sInfo:!1,bAutoWidth:!1,iDisplayLength:+EE.file.perPage,aoColumns:MyCols,oLanguage:{sZeroRecords:EE.lang.noEntries,oPaginate:{sFirst:'<img src="'+EE.file.themeUrl+'images/pagination_first_button.gif" width="13" height="13" alt="&lt; &lt;" />',sPrevious:'<img src="'+EE.file.themeUrl+'images/pagination_prev_button.gif" width="13" height="13" alt="&lt; &lt;" />',
sNext:'<img src="'+EE.file.themeUrl+'images/pagination_next_button.gif" width="13" height="13" alt="&lt; &lt;" />',sLast:'<img src="'+EE.file.themeUrl+'images/pagination_last_button.gif" width="13" height="13" alt="&lt; &lt;" />'}},bProcessing:!0,bServerSide:!0,sAjaxSource:EE.BASE+"&C=content_files&M=file_ajax_filter&time="+(new Date).getTime(),fnServerData:function(x,e,o){function p(){return $(q).data("user_data")=="n"?"":q.value}var n=+EE.file.pipe,l=!1,y=i(e,"sEcho"),g=i(e,"iDisplayStart"),j=i(e,
"iDisplayLength"),h=g+j,q=document.getElementById("keywords"),r=document.getElementById("file_type"),s=document.getElementById("dir_id"),t=document.getElementById("cat_id"),u=document.getElementById("date_range"),v=document.getElementById("search_in"),w=document.getElementById("file_type");e.push({name:"keywords",value:p()},{name:"type",value:r.value},{name:"dir_id",value:s.value},{name:"cat_id",value:t.value},{name:"date_range",value:u.value},{name:"search_in",value:v.value},{name:"file_type",value:w.value});
b=g;if(a<0||g<a||h>f)l=!0;if(c&&!l)for(var h=0,z=e.length;h<z;h++)if(e[h].name!="iDisplayStart"&&e[h].name!="iDisplayLength"&&e[h].name!="sEcho"&&e[h].value!=c[h].value){l=!0;break}c=e.slice();l?(g<a&&(g-=j*(n-1),g<0&&(g=0)),a=g,f=g+j*n,d=i(e,"iDisplayLength"),k(e,"iDisplayStart",g),k(e,"iDisplayLength",j*n),e.push({name:"keywords",value:p()},{name:"type",value:r.value},{name:"dir_id",value:s.value},{name:"cat_id",value:t.value},{name:"date_range",value:u.value},{name:"search_in",value:v.value},{name:"file_type",
value:w.value}),$.getJSON(x,e,function(c){m=jQuery.extend(!0,{},c);a!=b&&c.aaData.splice(0,b-a);c.aaData.splice(d,c.aaData.length);o(c)})):(json=jQuery.extend(!0,{},m),json.sEcho=y,json.aaData.splice(0,g-a),json.aaData.splice(j,json.aaData.length),o(json))}});$("#keywords").keyup(function(){oTable.fnDraw()});$("select#dir_id, select#cat_id, select#file_type, select#date_range").change(function(){oTable.fnDraw()})};
