/**
 * @package     ZOOlanders
 * @version     3.3.26
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

angular.module("widgetkit").controller("zooproCtrl",["$scope","Application","$http","$filter",function(e,i,a,n){var t=this,o=e.content.data,r=[{id:"title",name:"title",def:"title",core:!0},{id:"content",name:"content",def:"content",core:!0},{id:"media",name:"media",def:"media",core:!0},{id:"media2",name:"media2",def:"media2",core:!0},{id:"location",name:"location",def:"location",core:!0},{id:"link",name:"link",def:"link",core:!0},{id:"date",name:"date",def:"date",core:!0},{id:"author",name:"author",def:"author",core:!0},{id:"categories",name:"categories",def:"categories",core:!0},{id:"tags",name:"tags",def:"tags",core:!0}];o.mapping=o.mapping?o.mapping:{},o.fields=o.fields?r.concat(o.fields):r,"string"==typeof o.order&&(o.order={_reversed:!1,_random:!1,_alphanumeric:!1,core:"_itemname"});var d=[];o.fields=o.fields.filter(function(e){return-1==d.indexOf(e.id)&&(d.push(e.id),!0)});try{e.zoo=JSON.parse(angular.element('script[type="zoopro/config"]')[0].innerHTML)}catch(i){e.zoo={}}var c=o.application;e.$watch("content.data.application",function(){if(o.application&&c!=o.application){var i=n("toArray")(e.zoo[o.application].categories)[0];o.category=i?i.id:"";var a=n("toArray")(e.zoo[o.application].types)[0];a&&(o.type=a.id,o.order.core=o.order.core?o.order.core:"_itemname"),angular.forEach(e.zoo[o.application].types,function(e){angular.forEach(o.fields,function(i){o.mapping[e.id]=o.mapping[e.id]?o.mapping[e.id]:{},void 0==o.mapping[e.id][i.id]&&(o.mapping[e.id][i.id]=i.def)})})}}),e.$watch("content.data.mode",function(){angular.element('#zoo-mapping-types li[data-id="'+o.type+'"]').addClass("uk-active")}),t.addField=function(){var e=angular.element("#zoo-field-new")[0];e.value.length&&(o.fields.push({id:e.value,name:e.value}),e.value="")},t.deleteField=function(e){o.fields.splice(o.fields.indexOf(e),1)}}]).filter("zoo",function(){return function(e,i){return e&&angular.forEach(i,function(i,a){e=e.filter(function(e){return e[a]==i})}),e}});

/**
 * @package     ZOOlanders
 * @version     3.3.26
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

angular.module("widgetkit").controller("zlpickerCtrl",["$scope","Application",function(t,i){var e=this,n=window.zlwk.env;t.widgets=i.config.widgets,t.view=n.attrs&&n.attrs.widget?"":"widgets",e.selectWidget=function(i){t.widget=angular.extend({},i,{data:angular.extend({},i.settings,n.attrs)}),t.view=""},e.save=function(i){var e={widget:t.widget.name};angular.forEach(t.widget.data,function(i,n){t.widget.settings.hasOwnProperty(n)&&t.widget.settings[n]!=i&&(e[n]=i)}),n.update(e)},e.cancel=function(){n.cancel(),t.view=""},e.listWidgets=function(){t.view="widgets"},n.attrs.widget&&e.selectWidget(i.config.widgets[n.attrs.widget]),n.modal.show()}]),function(t,i){var e={init:function(i,e){var n=t(this.tmpl).appendTo("body");this.attrs=i,this.cb=e,this.modal=t.UIkit.modal(n),this.modal.on("hide.uk.modal",function(){n.remove()}),t.UIkit.domObserve(n,function(){var i=this;t.UIkit.domObservers.forEach(function(t){t(i)})}),angular.bootstrap(n,["widgetkit"])},update:function(t){this.cb(t),this.modal.hide()},cancel:function(){this.modal.hide()},tmpl:'<div class="uk-modal"><div style="width: 800px;" class="uk-modal-dialog"><div ng-include="\'zoopro.picker\'"></div></div></div>'};t(function(){i.zlwk={env:e}})}(jQuery,window);

