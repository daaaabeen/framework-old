/**
 * @license Copyright (c) 2003-2012, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For the complete reference:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config
	
	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [ 'find', 'selection',  'spellchecker' ] },
		{ name: 'links' },
		{ name: 'forms' },
		{ name: 'tools' },
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others' },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align' ] },
		{ name: 'insert' },
		//{ name: 'styles' },
		//{ name: 'colors' },
		{ name: 'about' }
	];

	// Remove some buttons, provided by the standard plugins, which we don't
	// need to have in the Standard(s) toolbar.
	config.removeButtons = 'Underline,Subscript,Superscript,SpecialChar';
	config.extraPlugins = 'jme';
	//config.filebrowserImageUploadUrl = 'aaa.php';
	config.toolbarLocation = 'top';//可选：bottom   
	config.autoUpdateElement = true;
	config.entities_greek = true;
	config.width = 700;
	//工具栏默认是否展开  
	config.toolbarStartupExpanded = true;
	config.enableTabKeyTools = false;
	
	//强制清楚格式
	//config.forcePasteAsPlainText = true;
	config.filebrowserBrowseUrl =  JMEditor_BasePath +'ckfinder/ckfinder.html',
	config.filebrowserImageBrowseUrl =  JMEditor_BasePath +'ckfinder/ckfinder.html?Type=Images',
	config.filebrowserFlashBrowseUrl =  JMEditor_BasePath +'ckfinder/ckfinder.html?Type=Flash',
	config.filebrowserUploadUrl = JMEditor_BasePath +'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
	config.filebrowserImageUploadUrl  =  JMEditor_BasePath +'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
	config.filebrowserFlashUploadUrl  =  JMEditor_BasePath +'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
};
