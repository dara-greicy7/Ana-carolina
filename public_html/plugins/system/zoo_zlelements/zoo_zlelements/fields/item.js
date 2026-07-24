/**
 * @package     ZL Elements
 * @version     3.3.0
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

function selectZooItem(e,t,i){jQuery("#"+i+"_id").val(e),jQuery("#"+i+"_name").val(t),SqueezeBox.close?SqueezeBox.close():$("sbox-window").close()}!function(e){var t=function(){};e.extend(t.prototype,{name:"ZooItem",options:{url:null,msgSelectItem:"Select Item"},initialize:function(t,i){this.options=e.extend({},this.options,i);var n=this;this.app=t.find(".application select"),this.item=t.find("div.item"),this.app.bind("change",function(){n.setValues(),n.item.length&&(n.item.find("input:hidden").val(""),n.item.find("input:text").val(n.options.msgSelectItem))}),n.setValues()},setValues:function(){var e=this.app.val();this.item.length&&e&&this.item.find("a").attr("href",this.options.url+"&app_id="+e)}}),e.fn[t.prototype.name]=function(){var i=arguments,n=i[0]?i[0]:null;return this.each(function(){var o=e(this);if(t.prototype[n]&&o.data(t.prototype.name)&&"initialize"!=n)o.data(t.prototype.name)[n].apply(o.data(t.prototype.name),Array.prototype.slice.call(i,1));else if(!n||e.isPlainObject(n)){var a=new t;t.prototype.initialize&&a.initialize.apply(a,e.merge([o],i)),o.data(t.prototype.name,a)}else e.error("Method "+n+" does not exist on jQuery."+t.name)})}}(jQuery);