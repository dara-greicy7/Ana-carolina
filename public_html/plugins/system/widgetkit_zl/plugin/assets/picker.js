/**
 * @package     ZOOlanders
 * @version     3.3.26
 * @author      ZOOlanders - http://zoolanders.com
 * @license     GNU General Public License v2 or later
 */

angular.module("widgetkit").controller("zlpickerCtrl",["$scope","Application",function(t,i){var e=this,n=window.zlwk.env;t.widgets=i.config.widgets,t.view=n.attrs&&n.attrs.widget?"":"widgets",e.selectWidget=function(i){t.widget=angular.extend({},i,{data:angular.extend({},i.settings,n.attrs)}),t.view=""},e.save=function(i){var e={widget:t.widget.name};angular.forEach(t.widget.data,function(i,n){t.widget.settings.hasOwnProperty(n)&&t.widget.settings[n]!=i&&(e[n]=i)}),n.update(e)},e.cancel=function(){n.cancel(),t.view=""},e.listWidgets=function(){t.view="widgets"},n.attrs.widget&&e.selectWidget(i.config.widgets[n.attrs.widget]),n.modal.show()}]),function(t,i){var e={init:function(i,e){var n=t(this.tmpl).appendTo("body");this.attrs=i,this.cb=e,this.modal=t.UIkit.modal(n),this.modal.on("hide.uk.modal",function(){n.remove()}),t.UIkit.domObserve(n,function(){var i=this;t.UIkit.domObservers.forEach(function(t){t(i)})}),angular.bootstrap(n,["widgetkit"])},update:function(t){this.cb(t),this.modal.hide()},cancel:function(){this.modal.hide()},tmpl:'<div class="uk-modal"><div style="width: 800px;" class="uk-modal-dialog"><div ng-include="\'zoopro.picker\'"></div></div></div>'};t(function(){i.zlwk={env:e}})}(jQuery,window);