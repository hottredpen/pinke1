define(["jquery","ueditor"],function(n,t){var e=window.g_ueditor_arr||[],t={createObj:function(){function t(){n(function(){var t=new UE.ui.Editor;t.render(i.contentId);var r={ueditor_obj:t};e.push(r),window.g_ueditor_arr=e,n(document).on("clear_UEditor_"+i.name,function(){t.setContent("")})})}function r(){}var o={},i=(n(document),{contentId:"",name:""});return o.init=function(e){i=n.extend({},i,e),t(),r()},o}};return t});