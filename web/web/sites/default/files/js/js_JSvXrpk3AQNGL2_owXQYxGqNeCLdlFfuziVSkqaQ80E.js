/* @license GNU-GPL-2.0-or-later https://www.drupal.org/licensing/faq */
(function($,Drupal,debounce){const cache={right:0,left:0,bottom:0,top:0};const cssVarPrefix='--drupal-displace-offset';const documentStyle=document.documentElement.style;const offsetKeys=Object.keys(cache);const offsetProps={};offsetKeys.forEach((edge)=>{offsetProps[edge]={enumerable:true,get(){return cache[edge];},set(value){if(value!==cache[edge])documentStyle.setProperty(`${cssVarPrefix}-${edge}`,`${value}px`);cache[edge]=value;}};});const offsets=Object.seal(Object.defineProperties({},offsetProps));function getRawOffset(el,edge){const $el=$(el);const documentElement=document.documentElement;let displacement=0;const horizontal=edge==='left'||edge==='right';let placement=$el.offset()[horizontal?'left':'top'];placement-=window[`scroll${horizontal?'X':'Y'}`]||document.documentElement[`scroll${horizontal?'Left':'Top'}`]||0;switch(edge){case 'top':displacement=placement+$el.outerHeight();break;case 'left':displacement=placement+$el.outerWidth();break;case 'bottom':displacement=documentElement.clientHeight-placement;break;case 'right':displacement=documentElement.clientWidth-placement;break;default:displacement=0;}return displacement;}function calculateOffset(edge){let edgeOffset=0;const displacingElements=document.querySelectorAll(`[data-offset-${edge}]`);const n=displacingElements.length;for(let i=0;i<n;i++){const el=displacingElements[i];if(el.style.display==='none')continue;let displacement=parseInt(el.getAttribute(`data-offset-${edge}`),10);if(isNaN(displacement))displacement=getRawOffset(el,edge);edgeOffset=Math.max(edgeOffset,displacement);}return edgeOffset;}function displace(broadcast=true){const newOffsets={};offsetKeys.forEach((edge)=>{newOffsets[edge]=calculateOffset(edge);});offsetKeys.forEach((edge)=>{offsets[edge]=newOffsets[edge];});if(broadcast)$(document).trigger('drupalViewportOffsetChange',offsets);return offsets;}Drupal.behaviors.drupalDisplace={attach(){if(this.displaceProcessed)return;this.displaceProcessed=true;$(window).on('resize.drupalDisplace',debounce(displace,200));}};Drupal.displace=displace;Object.defineProperty(Drupal.displace,'offsets',{value:offsets,writable:false});Drupal.displace.calculateOffset=calculateOffset;})(jQuery,Drupal,Drupal.debounce);;
(($,Drupal,{isTabbable})=>{$.extend($.expr[':'],{tabbable(element){Drupal.deprecationError({message:'The :tabbable selector is deprecated in Drupal 9.2.0 and will be removed in Drupal 11.0.0. Use the core/tabbable library instead. See https://www.drupal.org/node/3183730'});return isTabbable(element);}});})(jQuery,Drupal,window.tabbable);;
(($)=>{let cachedScrollbarWidth=null;const {max,abs}=Math;const regexHorizontal=/left|center|right/;const regexVertical=/top|center|bottom/;const regexOffset=/[+-]\d+(\.[\d]+)?%?/;const regexPosition=/^\w+/;const regexPercent=/%$/;const _position=$.fn.position;function getOffsets(offsets,width,height){return [parseFloat(offsets[0])*(regexPercent.test(offsets[0])?width/100:1),parseFloat(offsets[1])*(regexPercent.test(offsets[1])?height/100:1)];}function parseCss(element,property){return parseInt(window.getComputedStyle(element)[property],10)||0;}function getDimensions(elem){const raw=elem[0];if(raw.nodeType===9)return {width:elem.width(),height:elem.height(),offset:{top:0,left:0}};if($.isWindow(raw))return {width:elem.width(),height:elem.height(),offset:{top:elem.scrollTop(),left:elem.scrollLeft()}};if(raw.preventDefault)return {width:0,height:0,offset:{top:raw.pageY,left:raw.pageX}};return {width:elem.outerWidth(),height:elem.outerHeight(),offset:elem.offset()};}const collisions={fit:{left(position,data){const {within}=data;const withinOffset=within.isWindow?within.scrollLeft:within.offset.left;const outerWidth=within.width;const collisionPosLeft=position.left-data.collisionPosition.marginLeft;const overLeft=withinOffset-collisionPosLeft;const overRight=collisionPosLeft+data.collisionWidth-outerWidth-withinOffset;let newOverRight;if(data.collisionWidth>outerWidth)if(overLeft>0&&overRight<=0){newOverRight=position.left+overLeft+data.collisionWidth-outerWidth-withinOffset;position.left+=overLeft-newOverRight;}else if(overRight>0&&overLeft<=0)position.left=withinOffset;else if(overLeft>overRight)position.left=withinOffset+outerWidth-data.collisionWidth;else position.left=withinOffset;else if(overLeft>0)position.left+=overLeft;else if(overRight>0)position.left-=overRight;else position.left=max(position.left-collisionPosLeft,position.left);},top(position,data){const {within}=data;const withinOffset=within.isWindow?within.scrollTop:within.offset.top;const outerHeight=data.within.height;const collisionPosTop=position.top-data.collisionPosition.marginTop;const overTop=withinOffset-collisionPosTop;const overBottom=collisionPosTop+data.collisionHeight-outerHeight-withinOffset;let newOverBottom;if(data.collisionHeight>outerHeight)if(overTop>0&&overBottom<=0){newOverBottom=position.top+overTop+data.collisionHeight-outerHeight-withinOffset;position.top+=overTop-newOverBottom;}else if(overBottom>0&&overTop<=0)position.top=withinOffset;else if(overTop>overBottom)position.top=withinOffset+outerHeight-data.collisionHeight;else position.top=withinOffset;else if(overTop>0)position.top+=overTop;else if(overBottom>0)position.top-=overBottom;else position.top=max(position.top-collisionPosTop,position.top);}},flip:{left(position,data){const {within}=data;const withinOffset=within.offset.left+within.scrollLeft;const outerWidth=within.width;const offsetLeft=within.isWindow?within.scrollLeft:within.offset.left;const collisionPosLeft=position.left-data.collisionPosition.marginLeft;const overLeft=collisionPosLeft-offsetLeft;const overRight=collisionPosLeft+data.collisionWidth-outerWidth-offsetLeft;const myOffset=data.my[0]==='left'?-data.elemWidth:data.my[0]==='right'?data.elemWidth:0;const atOffset=data.at[0]==='left'?data.targetWidth:data.at[0]==='right'?-data.targetWidth:0;const offset=-2*data.offset[0];let newOverRight;let newOverLeft;if(overLeft<0){newOverRight=position.left+myOffset+atOffset+offset+data.collisionWidth-outerWidth-withinOffset;if(newOverRight<0||newOverRight<abs(overLeft))position.left+=myOffset+atOffset+offset;}else{if(overRight>0){newOverLeft=position.left-data.collisionPosition.marginLeft+myOffset+atOffset+offset-offsetLeft;if(newOverLeft>0||abs(newOverLeft)<overRight)position.left+=myOffset+atOffset+offset;}}},top(position,data){const {within}=data;const withinOffset=within.offset.top+within.scrollTop;const outerHeight=within.height;const offsetTop=within.isWindow?within.scrollTop:within.offset.top;const collisionPosTop=position.top-data.collisionPosition.marginTop;const overTop=collisionPosTop-offsetTop;const overBottom=collisionPosTop+data.collisionHeight-outerHeight-offsetTop;const top=data.my[1]==='top';const myOffset=top?-data.elemHeight:data.my[1]==='bottom'?data.elemHeight:0;const atOffset=data.at[1]==='top'?data.targetHeight:data.at[1]==='bottom'?-data.targetHeight:0;const offset=-2*data.offset[1];let newOverTop;let newOverBottom;if(overTop<0){newOverBottom=position.top+myOffset+atOffset+offset+data.collisionHeight-outerHeight-withinOffset;if(newOverBottom<0||newOverBottom<abs(overTop))position.top+=myOffset+atOffset+offset;}else{if(overBottom>0){newOverTop=position.top-data.collisionPosition.marginTop+myOffset+atOffset+offset-offsetTop;if(newOverTop>0||abs(newOverTop)<overBottom)position.top+=myOffset+atOffset+offset;}}}},flipfit:{left(...args){collisions.flip.left.apply(this,args);collisions.fit.left.apply(this,args);},top(...args){collisions.flip.top.apply(this,args);collisions.fit.top.apply(this,args);}}};$.position={scrollbarWidth(){if(cachedScrollbarWidth!==undefined)return cachedScrollbarWidth;const div=$('<div '+"style='display:block;position:absolute;width:50px;height:50px;overflow:hidden;'>"+"<div style='height:100px;width:auto;'></div></div>");const innerDiv=div.children()[0];$('body').append(div);const w1=innerDiv.offsetWidth;div[0].style.overflow='scroll';let w2=innerDiv.offsetWidth;if(w1===w2)w2=div[0].clientWidth;div.remove();cachedScrollbarWidth=w1-w2;return cachedScrollbarWidth;},getScrollInfo(within){const overflowX=within.isWindow||within.isDocument?'':window.getComputedStyle(within.element[0])['overflow-x'];const overflowY=within.isWindow||within.isDocument?'':window.getComputedStyle(within.element[0])['overflow-y'];const hasOverflowX=overflowX==='scroll'||(overflowX==='auto'&&within.width<within.element[0].scrollWidth);const hasOverflowY=overflowY==='scroll'||(overflowY==='auto'&&within.height<within.element[0].scrollHeight);return {width:hasOverflowY?$.position.scrollbarWidth():0,height:hasOverflowX?$.position.scrollbarWidth():0};},getWithinInfo(element){const withinElement=$(element||window);const isWindow=$.isWindow(withinElement[0]);const isDocument=!!withinElement[0]&&withinElement[0].nodeType===9;const hasOffset=!isWindow&&!isDocument;return {element:withinElement,isWindow,isDocument,offset:hasOffset?$(element).offset():{left:0,top:0},scrollLeft:withinElement.scrollLeft(),scrollTop:withinElement.scrollTop(),width:withinElement.outerWidth(),height:withinElement.outerHeight()};}};$.fn.position=function(options){if(!options||!options.of)return _position.apply(this,arguments);options=$.extend({},options);const within=$.position.getWithinInfo(options.within);const scrollInfo=$.position.getScrollInfo(within);const collision=(options.collision||'flip').split(' ');const offsets={};const target=typeof options.of==='string'?$(document).find(options.of):$(options.of);const dimensions=getDimensions(target);const targetWidth=dimensions.width;const targetHeight=dimensions.height;const targetOffset=dimensions.offset;if(target[0].preventDefault)options.at='left top';const basePosition=$.extend({},targetOffset);$.each(['my','at'],function(){let pos=(options[this]||'').split(' ');if(pos.length===1)pos=regexHorizontal.test(pos[0])?pos.concat(['center']):regexVertical.test(pos[0])?['center'].concat(pos):['center','center'];pos[0]=regexHorizontal.test(pos[0])?pos[0]:'center';pos[1]=regexVertical.test(pos[1])?pos[1]:'center';const horizontalOffset=regexOffset.exec(pos[0]);const verticalOffset=regexOffset.exec(pos[1]);offsets[this]=[horizontalOffset?horizontalOffset[0]:0,verticalOffset?verticalOffset[0]:0];options[this]=[regexPosition.exec(pos[0])[0],regexPosition.exec(pos[1])[0]];});if(collision.length===1)collision[1]=collision[0];if(options.at[0]==='right')basePosition.left+=targetWidth;else{if(options.at[0]==='center')basePosition.left+=targetWidth/2;}if(options.at[1]==='bottom')basePosition.top+=targetHeight;else{if(options.at[1]==='center')basePosition.top+=targetHeight/2;}const atOffset=getOffsets(offsets.at,targetWidth,targetHeight);basePosition.left+=atOffset[0];basePosition.top+=atOffset[1];return this.each(function(){let using;const elem=$(this);const elemWidth=elem.outerWidth();const elemHeight=elem.outerHeight();const marginLeft=parseCss(this,'marginLeft');const marginTop=parseCss(this,'marginTop');const collisionWidth=elemWidth+marginLeft+parseCss(this,'marginRight')+scrollInfo.width;const collisionHeight=elemHeight+marginTop+parseCss(this,'marginBottom')+scrollInfo.height;const position=$.extend({},basePosition);const myOffset=getOffsets(offsets.my,elem.outerWidth(),elem.outerHeight());if(options.my[0]==='right')position.left-=elemWidth;else{if(options.my[0]==='center')position.left-=elemWidth/2;}if(options.my[1]==='bottom')position.top-=elemHeight;else{if(options.my[1]==='center')position.top-=elemHeight/2;}position.left+=myOffset[0];position.top+=myOffset[1];const collisionPosition={marginLeft,marginTop};$.each(['left','top'],function(i,dir){if(collisions[collision[i]])collisions[collision[i]][dir](position,{targetWidth,targetHeight,elemWidth,elemHeight,collisionPosition,collisionWidth,collisionHeight,offset:[atOffset[0]+myOffset[0],atOffset[1]+myOffset[1]],my:options.my,at:options.at,within,elem});});if(options.using)using=function(props){const left=targetOffset.left-position.left;const right=left+targetWidth-elemWidth;const top=targetOffset.top-position.top;const bottom=top+targetHeight-elemHeight;const feedback={target:{element:target,left:targetOffset.left,top:targetOffset.top,width:targetWidth,height:targetHeight},element:{element:elem,left:position.left,top:position.top,width:elemWidth,height:elemHeight},horizontal:right<0?'left':left>0?'right':'center',vertical:bottom<0?'top':top>0?'bottom':'middle'};if(targetWidth<elemWidth&&abs(left+right)<targetWidth)feedback.horizontal='center';if(targetHeight<elemHeight&&abs(top+bottom)<targetHeight)feedback.vertical='middle';if(max(abs(left),abs(right))>max(abs(top),abs(bottom)))feedback.important='horizontal';else feedback.important='vertical';options.using.call(this,props,feedback);};elem.offset($.extend(position,{using}));});};if(!$.hasOwnProperty('ui'))$.ui={};$.ui.position=collisions;})(jQuery);;
(function($,Drupal,drupalSettings,bodyScrollLock){drupalSettings.dialog={autoOpen:true,dialogClass:'',buttonClass:'button',buttonPrimaryClass:'button--primary',close(event){Drupal.dialog(event.target).close();Drupal.detachBehaviors(event.target,null,'unload');}};Drupal.dialog=function(element,options){let undef;const $element=$(element);const dialog={open:false,returnValue:undef};function openDialog(settings){settings=$.extend({},drupalSettings.dialog,options,settings);$(window).trigger('dialog:beforecreate',[dialog,$element,settings]);$element.dialog(settings);dialog.open=true;if(settings.modal)bodyScrollLock.lock($element.get(0));$(window).trigger('dialog:aftercreate',[dialog,$element,settings]);}function closeDialog(value){$(window).trigger('dialog:beforeclose',[dialog,$element]);bodyScrollLock.clearBodyLocks();$element.dialog('close');dialog.returnValue=value;dialog.open=false;$(window).trigger('dialog:afterclose',[dialog,$element]);}dialog.show=()=>{openDialog({modal:false});};dialog.showModal=()=>{openDialog({modal:true});};dialog.close=closeDialog;return dialog;};})(jQuery,Drupal,drupalSettings,bodyScrollLock);;
(function($,Drupal,drupalSettings,debounce,displace){drupalSettings.dialog=$.extend({autoResize:true,maxHeight:'95%'},drupalSettings.dialog);function resetPosition(options){const offsets=displace.offsets;const left=offsets.left-offsets.right;const top=offsets.top-offsets.bottom;const leftString=`${(left>0?'+':'-')+Math.abs(Math.round(left/2))}px`;const topString=`${(top>0?'+':'-')+Math.abs(Math.round(top/2))}px`;options.position={my:`center${left!==0?leftString:''} center${top!==0?topString:''}`,of:window};return options;}function resetSize(event){const positionOptions=['width','height','minWidth','minHeight','maxHeight','maxWidth','position'];let adjustedOptions={};let windowHeight=$(window).height();let option;let optionValue;let adjustedValue;for(let n=0;n<positionOptions.length;n++){option=positionOptions[n];optionValue=event.data.settings[option];if(optionValue)if(typeof optionValue==='string'&&/%$/.test(optionValue)&&/height/i.test(option)){windowHeight-=displace.offsets.top+displace.offsets.bottom;adjustedValue=parseInt(0.01*parseInt(optionValue,10)*windowHeight,10);if(option==='height'&&event.data.$element.parent().outerHeight()<adjustedValue)adjustedValue='auto';adjustedOptions[option]=adjustedValue;}}if(!event.data.settings.modal)adjustedOptions=resetPosition(adjustedOptions);event.data.$element.dialog('option',adjustedOptions).trigger('dialogContentResize');}$(window).on({'dialog:aftercreate':function(event,dialog,$element,settings){const autoResize=debounce(resetSize,20);const eventData={settings,$element};if(settings.autoResize===true||settings.autoResize==='true'){const uiDialog=$element.dialog('option',{resizable:false,draggable:false}).dialog('widget');uiDialog[0].style.position='fixed';$(window).on('resize.dialogResize scroll.dialogResize',eventData,autoResize).trigger('resize.dialogResize');$(document).on('drupalViewportOffsetChange.dialogResize',eventData,autoResize);}},'dialog:beforeclose':function(event,dialog,$element){$(window).off('.dialogResize');$(document).off('.dialogResize');}});})(jQuery,Drupal,drupalSettings,Drupal.debounce,Drupal.displace);;
(function($,{tabbable,isTabbable}){$.widget('ui.dialog',$.ui.dialog,{options:{buttonClass:'button',buttonPrimaryClass:'button--primary'},_createButtons(){const opts=this.options;let primaryIndex;let index;const il=opts.buttons.length;for(index=0;index<il;index++)if(opts.buttons[index].primary&&opts.buttons[index].primary===true){primaryIndex=index;delete opts.buttons[index].primary;break;}this._super();const $buttons=this.uiButtonSet.children().addClass(opts.buttonClass);if(typeof primaryIndex!=='undefined')$buttons.eq(index).addClass(opts.buttonPrimaryClass);},_focusTabbable(){let hasFocus=this._focusedElement?this._focusedElement.get(0):null;if(!hasFocus)hasFocus=this.element.find('[autofocus]').get(0);if(!hasFocus){const $elements=[this.element,this.uiDialogButtonPane];for(let i=0;i<$elements.length;i++){const element=$elements[i].get(0);if(element){const elementTabbable=tabbable(element);hasFocus=elementTabbable.length?elementTabbable[0]:null;}if(hasFocus)break;}}if(!hasFocus){const closeBtn=this.uiDialogTitlebarClose.get(0);hasFocus=closeBtn&&isTabbable(closeBtn)?closeBtn:null;}if(!hasFocus)hasFocus=this.uiDialog.get(0);$(hasFocus).eq(0).trigger('focus');}});})(jQuery,window.tabbable);;
(($)=>{$.widget('ui.dialog',$.ui.dialog,{_allowInteraction(event){if(event.target.classList===undefined)return this._super(event);return event.target.classList.contains('ck')||this._super(event);}});})(jQuery);;
(function($,Drupal,{focusable}){Drupal.behaviors.dialog={attach(context,settings){const $context=$(context);if(!$('#drupal-modal').length)$('<div id="drupal-modal" class="ui-front"></div>').hide().appendTo('body');const $dialog=$context.closest('.ui-dialog-content');if($dialog.length){if($dialog.dialog('option','drupalAutoButtons'))$dialog.trigger('dialogButtonsChange');setTimeout(function(){if(!$dialog[0].contains(document.activeElement)){$dialog.dialog('instance')._focusedElement=null;$dialog.dialog('instance')._focusTabbable();}},0);}const originalClose=settings.dialog.close;settings.dialog.close=function(event,...args){originalClose.apply(settings.dialog,[event,...args]);const $element=$(event.target);const ajaxContainer=$element.data('uiDialog')?$element.data('uiDialog').opener.closest('[data-drupal-ajax-container]'):[];if(ajaxContainer.length&&(document.activeElement===document.body||$(document.activeElement).not(':visible'))){const focusableChildren=focusable(ajaxContainer[0]);if(focusableChildren.length>0)setTimeout(()=>{focusableChildren[0].focus();},0);}$(event.target).remove();};},prepareDialogButtons($dialog){const buttons=[];const $buttons=$dialog.find('.form-actions input[type=submit], .form-actions a.button, .form-actions a.action-link');$buttons.each(function(){const $originalButton=$(this);this.style.display='none';buttons.push({text:$originalButton.html()||$originalButton.attr('value'),class:$originalButton.attr('class'),'data-once':$originalButton.data('once'),click(e){if($originalButton[0].tagName==='A')$originalButton[0].click();else $originalButton.trigger('mousedown').trigger('mouseup').trigger('click');e.preventDefault();}});});return buttons;}};Drupal.AjaxCommands.prototype.openDialog=function(ajax,response,status){if(!response.selector)return false;let $dialog=$(response.selector);if(!$dialog.length)$dialog=$(`<div id="${response.selector.replace(/^#/,'')}" class="ui-front"></div>`).appendTo('body');if(!ajax.wrapper)ajax.wrapper=$dialog.attr('id');response.command='insert';response.method='html';ajax.commands.insert(ajax,response,status);response.dialogOptions=response.dialogOptions||{};if(typeof response.dialogOptions.drupalAutoButtons==='undefined')response.dialogOptions.drupalAutoButtons=true;else if(response.dialogOptions.drupalAutoButtons==='false')response.dialogOptions.drupalAutoButtons=false;else response.dialogOptions.drupalAutoButtons=!!response.dialogOptions.drupalAutoButtons;if(!response.dialogOptions.buttons&&response.dialogOptions.drupalAutoButtons)response.dialogOptions.buttons=Drupal.behaviors.dialog.prepareDialogButtons($dialog);$dialog.on('dialogButtonsChange',()=>{const buttons=Drupal.behaviors.dialog.prepareDialogButtons($dialog);$dialog.dialog('option','buttons',buttons);});response.dialogOptions=response.dialogOptions||{};const dialog=Drupal.dialog($dialog.get(0),response.dialogOptions);if(response.dialogOptions.modal)dialog.showModal();else dialog.show();$dialog.parent().find('.ui-dialog-buttonset').addClass('form-actions');};Drupal.AjaxCommands.prototype.closeDialog=function(ajax,response,status){const $dialog=$(response.selector);if($dialog.length){Drupal.dialog($dialog.get(0)).close();if(!response.persist)$dialog.remove();}$dialog.off('dialogButtonsChange');};Drupal.AjaxCommands.prototype.setDialogOption=function(ajax,response,status){const $dialog=$(response.selector);if($dialog.length)$dialog.dialog('option',response.optionName,response.optionValue);};$(window).on('dialog:aftercreate',(e,dialog,$element,settings)=>{$element.on('click.dialog','.dialog-cancel',(e)=>{dialog.close('cancel');e.preventDefault();e.stopPropagation();});});$(window).on('dialog:beforeclose',(e,dialog,$element)=>{$element.off('.dialog');});})(jQuery,Drupal,window.tabbable);;
(function($,Drupal,drupalSettings,once){'use strict';Drupal.autosaveForm={timer:null,interval:null,onlyOnFormChange:false,autosaveFormRunning:false,autosaveFormInstances:{},initialized:false,formHasErrors:false,message:'',dialog_options:[],autosave_submit_class:'autosave-form-save',autosave_restore_class:'autosave-form-restore',autosave_reject_class:'autosave-form-reject',notification:{active:true,message:Drupal.t('Saving draft...'),delay:1000},form:null};Drupal.autosaveForm.beforeUnloadCalled=false;$(window).on('beforeunload pagehide',function(){Drupal.autosaveForm.beforeUnloadCalled=true;});Drupal.autosaveForm.defaultDialogOptions={open:function(){$(this).siblings('.ui-dialog-titlebar').remove();},modal:true,zIndex:10000,position:{my:'top',at:'top+25%'},autoOpen:true,width:'auto',resizable:false,closeOnEscape:false};Drupal.behaviors.autosaveForm={attach:function(context,settings){var $context=$(context);var autosave_submit=$context.find('form .'+Drupal.autosaveForm.autosave_submit_class);if(autosave_submit.length>0){Drupal.autosaveForm.form=$(autosave_submit[0]).parents('form.autosave-form');Drupal.autosaveForm.form.submit(function(){if(Drupal.autosaveForm.autosaveFormRunning){Drupal.autosaveForm.autosaveFormRunning=false;clearInterval(Drupal.autosaveForm.timer);Drupal.autosaveForm.timer=null;}});}if(settings.hasOwnProperty('autosaveForm'))$.extend(Drupal.autosaveForm,settings.autosaveForm);if(Drupal.autosaveForm.initialized)if(!Drupal.autosaveForm.autosaveFormRunning&&Drupal.autosaveForm.timer){clearInterval(Drupal.autosaveForm.timer);Drupal.autosaveForm.timer=null;}else return;if(autosave_submit.length===0||autosave_submit.is(':disabled')||autosave_submit.hasClass('is-disabled'))return;Drupal.autosaveForm.autosaveSubmit=autosave_submit;if(!Drupal.autosaveForm.initialized&&!Drupal.autosaveForm.autosaveFormRunning){Drupal.autosaveForm.initialized=true;$('<div id="autosave-notification" />').appendTo('body').append(Drupal.autosaveForm.notification.message);if(Drupal.autosaveForm.message&&!Drupal.autosaveForm.formHasErrors){var dialogOptions={buttons:{button_confirm:{text:Drupal.t('Resume editing'),class:'autosave-form-resume-button',click:function(){$('.'+Drupal.autosaveForm.autosave_restore_class).click();}},button_reject:{text:Drupal.t('Discard'),class:'autosave-form-reject-button',click:function(){triggerAjaxSubmitWithoutProgressIndication(Drupal.autosaveForm.autosave_reject_class,true);$(this).dialog('close');},primary:true}},close:function(event,ui){$(this).remove();$(context).find('.'+Drupal.autosaveForm.autosave_restore_class).remove();$(context).find('.'+Drupal.autosaveForm.autosave_reject_class).remove();$(context).find('.autosave-form-restore-discard').remove();autosavePeriodic();}};$.extend(true,dialogOptions,Drupal.autosaveForm.defaultDialogOptions,Drupal.autosaveForm.dialog_options);$('<div></div>').appendTo('body').html('<div>'+Drupal.autosaveForm.message+'</div>').dialog(dialogOptions);}else autosavePeriodic();}function findAjaxInstance(class_name){if(!Drupal.autosaveForm.autosaveFormInstances.hasOwnProperty(class_name)){var element=document.getElementsByClassName(class_name)[0];var ajax=null;var selector='#'+element.id;for(var index in Drupal.ajax.instances)if(Drupal.ajax.instances.hasOwnProperty(index)){var ajaxInstance=Drupal.ajax.instances[index];if(ajaxInstance&&(ajaxInstance.selector===selector)){ajax=ajaxInstance;break;}}Drupal.autosaveForm.autosaveFormInstances[class_name]=ajax;}return Drupal.autosaveForm.autosaveFormInstances[class_name];}function triggerAjaxSubmitWithoutProgressIndication(ajax_class,skip_checks=false){if(Drupal.autosaveForm.autosaveSubmit.is(':disabled')||Drupal.autosaveForm.autosaveSubmit.hasClass('is-disabled'))return;if(!skip_checks&&Drupal.autosaveForm.onlyOnFormChange&&!Drupal.autosaveForm.form.data('autosave-form-changed'))return;var ajax=findAjaxInstance(ajax_class);if(ajax){if(Drupal.autosaveForm.notification.active)$('#autosave-notification').fadeIn().delay(Drupal.autosaveForm.notification.delay).fadeOut();ajax.success=function(response,status){if(Drupal.autosaveForm.onlyOnFormChange&&Drupal.autosaveForm.form.data('autosave-form-changed'))Drupal.autosaveForm.form.data('autosave-form-changed',false);Drupal.Ajax.prototype.success.call(this,response,status);};ajax.options.error=function(xmlhttprequest,text_status,error_thrown){if(xmlhttprequest.status===0||xmlhttprequest.status>=400){Drupal.autosaveForm.autosaveFormRunning=false;clearInterval(Drupal.autosaveForm.timer);Drupal.autosaveForm.timer=null;if(!Drupal.autosaveForm.beforeUnloadCalled){var dialogOptions={buttons:{button_confirm:{text:Drupal.t('Ok'),primary:true,click:function(){$(this).dialog('close');}}}};$.extend(true,dialogOptions,Drupal.autosaveForm.defaultDialogOptions);$('<div></div>').appendTo('body').html('<div>'+Drupal.t('A server error occurred during autosaving the current page. As a result autosave is disabled. To activate it please revisit the page and continue the editing from the last autosaved state of the current page.')+'</div>').dialog(dialogOptions);}}};ajax.progress=false;$(ajax.element).trigger(ajax.element_settings?ajax.element_settings.event:ajax.elementSettings.event);}}function autosavePeriodic(){if(Drupal.autosaveForm.interval){Drupal.autosaveForm.autosaveFormRunning=true;if(Drupal.autosaveForm.interval>500)setTimeout(function(){triggerAjaxSubmitWithoutProgressIndication(Drupal.autosaveForm.autosave_submit_class,true);},500);Drupal.autosaveForm.timer=setInterval(function(){if(!Drupal.ajax.instances.some(isAjaxing))triggerAjaxSubmitWithoutProgressIndication(Drupal.autosaveForm.autosave_submit_class);},Drupal.autosaveForm.interval);}}function isAjaxing(instance){return instance&&instance.hasOwnProperty('ajaxing')&&instance.ajaxing===true;}}};Drupal.AjaxCommands.prototype.openAutosaveDisabledDialog=function(ajax,response,status){response.dialogOptions.buttons={button_confirm:{text:Drupal.t('Ok'),click:function(){$(this).dialog('close');}}};response.dialogOptions.open=function(){$(this).siblings('.ui-dialog-titlebar').find('.ui-dialog-titlebar-close').remove();};Drupal.AjaxCommands.prototype.openDialog(ajax,response,status);};Drupal.behaviors.autosaveFormMonitor={attach:function(context,settings){var $context=$(context);var $form=$context.find('form.autosave-form');if($form.length===0)var $form=$context.parents('form.autosave-form');if($form.length>0){if($context.find('.ajax-new-content').length>0)$form.data('autosave-form-changed',true);var inputs=$form.find(':input, [contenteditable="true"]').not('button, input[type="button"]');$(once('autosave-form-input-monitor',inputs)).on('change textInput input',function(event){var $form=$(event.target).parents('.autosave-form').first();if($form.length){var val=$(this).val();if(val!=$(this).attr('autosave-old-val')){$(this).attr('autosave-old-val',val);$form.data('autosave-form-changed',true);}}}).on('mousedown',function(event){if(event.target.type==='submit')$form.data('autosave-form-changed',true);});if(typeof CKEDITOR!=="undefined")CKEDITOR.on("instanceCreated",function(event){event.editor.on("change",function(event){if(typeof event.editor!=="undefined"&&typeof event.target==="undefined")event.target=event.editor.element.$;var $form=$(event.target).parents('.autosave-form').first();if($form.length)$form.data('autosave-form-changed',true);});});}}};})(jQuery,Drupal,drupalSettings,once);;
