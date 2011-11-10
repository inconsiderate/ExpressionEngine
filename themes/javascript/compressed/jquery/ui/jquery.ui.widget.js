/*!
 * jQuery UI Widget 1.8.16
 *
 * Copyright 2011, AUTHORS.txt (http://jqueryui.com/about)
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://jquery.org/license
 *
 * http://docs.jquery.com/UI/Widget
 */

(function(c,h){if(c.cleanData){var i=c.cleanData;c.cleanData=function(a){for(var b=0,d;(d=a[b])!=null;b++)try{c(d).triggerHandler("remove")}catch(e){}i(a)}}else{var j=c.fn.remove;c.fn.remove=function(a,b){return this.each(function(){b||(!a||c.filter(a,[this]).length)&&c("*",this).add([this]).each(function(){try{c(this).triggerHandler("remove")}catch(a){}});return j.call(c(this),a,b)})}}c.widget=function(a,b,d){var e=a.split(".")[0],f,a=a.split(".")[1];f=e+"-"+a;if(!d)d=b,b=c.Widget;c.expr[":"][f]=
function(b){return!!c.data(b,a)};c[e]=c[e]||{};c[e][a]=function(a,c){arguments.length&&this._createWidget(a,c)};b=new b;b.options=c.extend(!0,{},b.options);c[e][a].prototype=c.extend(!0,b,{namespace:e,widgetName:a,widgetEventPrefix:c[e][a].prototype.widgetEventPrefix||a,widgetBaseClass:f},d);c.widget.bridge(a,c[e][a])};c.widget.bridge=function(a,b){c.fn[a]=function(d){var e=typeof d==="string",f=Array.prototype.slice.call(arguments,1),g=this,d=!e&&f.length?c.extend.apply(null,[!0,d].concat(f)):d;
if(e&&d.charAt(0)==="_")return g;e?this.each(function(){var b=c.data(this,a),e=b&&c.isFunction(b[d])?b[d].apply(b,f):b;if(e!==b&&e!==h)return g=e,!1}):this.each(function(){var e=c.data(this,a);e?e.option(d||{})._init():c.data(this,a,new b(d,this))});return g}};c.Widget=function(a,b){arguments.length&&this._createWidget(a,b)};c.Widget.prototype={widgetName:"widget",widgetEventPrefix:"",options:{disabled:!1},_createWidget:function(a,b){c.data(b,this.widgetName,this);this.element=c(b);this.options=c.extend(!0,
{},this.options,this._getCreateOptions(),a);var d=this;this.element.bind("remove."+this.widgetName,function(){d.destroy()});this._create();this._trigger("create");this._init()},_getCreateOptions:function(){return c.metadata&&c.metadata.get(this.element[0])[this.widgetName]},_create:function(){},_init:function(){},destroy:function(){this.element.unbind("."+this.widgetName).removeData(this.widgetName);this.widget().unbind("."+this.widgetName).removeAttr("aria-disabled").removeClass(this.widgetBaseClass+
"-disabled ui-state-disabled")},widget:function(){return this.element},option:function(a,b){var d=a;if(arguments.length===0)return c.extend({},this.options);if(typeof a==="string"){if(b===h)return this.options[a];d={};d[a]=b}this._setOptions(d);return this},_setOptions:function(a){var b=this;c.each(a,function(a,c){b._setOption(a,c)});return this},_setOption:function(a,b){this.options[a]=b;a==="disabled"&&this.widget()[b?"addClass":"removeClass"](this.widgetBaseClass+"-disabled ui-state-disabled").attr("aria-disabled",
b);return this},enable:function(){return this._setOption("disabled",!1)},disable:function(){return this._setOption("disabled",!0)},_trigger:function(a,b,d){var e=this.options[a],b=c.Event(b);b.type=(a===this.widgetEventPrefix?a:this.widgetEventPrefix+a).toLowerCase();d=d||{};if(b.originalEvent)for(var a=c.event.props.length,f;a;)f=c.event.props[--a],b[f]=b.originalEvent[f];this.element.trigger(b,d);return!(c.isFunction(e)&&e.call(this.element[0],b,d)===!1||b.isDefaultPrevented())}}})(jQuery);
