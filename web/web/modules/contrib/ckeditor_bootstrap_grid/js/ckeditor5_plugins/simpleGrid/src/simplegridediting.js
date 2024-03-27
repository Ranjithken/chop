import {Plugin} from 'ckeditor5/src/core';
import {toWidget, toWidgetEditable} from 'ckeditor5/src/widget';
import {Widget} from 'ckeditor5/src/widget';
import InsertSimpleGridCommand from './insertsimplegridcommand';
import './theme/style.css';

export default class SimpleGridEditing extends Plugin {

  init() {
    this._defineSchema();
    this._defineConverters();

    this.editor.commands.add('insertSimpleBox', new InsertSimpleGridCommand(this.editor)); //registering command

    this.editor.config.define('simpleGridConfig', {
      types: [
        {
          'key': 'two-col', 'label': 'Add two column box',
          'text': 'default row two-col', 'class': 'col-md-6'
        },
        {
          'key': 'two-col-left', 'label': 'Add left column box',
          'text': 'layout2 row two-col-right-left', 'left_class': 'col-md-3', 'right_class': 'col-md-9'
        },
        {
          'key': 'two-col-right', 'label': 'Add right column box',
          'text': 'layout3 row two-col-right', 'left_class': 'col-md-9', 'right_class': 'col-md-3'
        },
        {
          'key': 'three-col', 'label': 'Add three column box',
          'text': 'layout4 row three-col', 'class': 'col-md-4',
        }
      ]
    });
  }

  _defineSchema() {
    const schema = this.editor.model.schema;

    schema.register('simpleGrid', {
      // Behaves like a self-contained object (e.g. an image).
      isObject: true,
      // Allow in places where other blocks are allowed (e.g. directly in the root).
      allowWhere: '$block',
      allowAttributes: ['attr', 'data-type']
    });

    schema.register('simpleGridLayout', {
      // Cannot be split or left by the caret.
      isLimit: true,
      allowIn: 'simpleGrid',
      // Allow content which is allowed in the root (e.g. paragraphs).
      allowContentOf: '$root',
      allowAttributes: ['class', 'class_right_column', 'class_left_column']
    });
  }

  _defineConverters() {
    const conversion = this.editor.conversion;

    // <simpleGrid> converters
    conversion.for('upcast').elementToElement({
      model: 'simpleGrid',
      view: {
        name: 'div',
        classes: 'simple-grid',
        'data-type': true
      }
    });
    conversion.for('dataDowncast').elementToElement({
      model: 'simpleGrid',
      view: (modelElement, {writer: viewWriter}) => {
        const div = setAttrsData(modelElement, viewWriter);

        // Enable widget handling on a placeholder element inside the editing view.
        return toWidget(div, viewWriter);
      }
    });
    conversion.for('editingDowncast').elementToElement({
      model: 'simpleGrid',
      view: (modelElement, {writer: viewWriter}) => {
        const div = setAttrsData(modelElement, viewWriter);

        // Enable widget handling on a placeholder element inside the editing view.
        return toWidget(div, viewWriter, {label: 'Grid Row'});
      }
    });

    // <simpleGridLayout> converters
    conversion.for('upcast').elementToElement({
      model: 'simpleGridLayout',
      view: {
        name: 'div',
        classes: 'simple-grid-layout'
      }
    });
    conversion.for('dataDowncast').elementToElement({
      model: 'simpleGridLayout',
      view: (modelElement, {writer: viewWriter}) => {
        // Note: You use a more specialized createEditableElement() method here.
        return viewWriter.createEditableElement('div', {
          class: setViewColumnsClasses(modelElement),
          'data-label': 'Column'
        });
      }
    });
    conversion.for('editingDowncast').elementToElement({
      model: 'simpleGridLayout',
      view: (modelElement, {writer: viewWriter}) => {
        // Note: You use a more specialized createEditableElement() method here.
        const div = viewWriter.createEditableElement('div', {
          class: setViewColumnsClasses(modelElement),
          'data-label': 'Column'
        });

        return toWidgetEditable(div, viewWriter);
      }
    });

    // Helper method for both downcast converters.
    function setAttrsData(modelItem, viewWriter) {
      //console.log( modelItem, viewWriter );
      let opt = {'class': 'simple-grid row', 'data-label': 'Grid'},
        preAttrs = null;
      const attr = modelItem.getAttribute('attr');
      if (attr && (typeof attr !== 'undefined')) {
        opt["class"] = opt["class"] + " type--" + attr;
        opt["data-type"] = attr;
      } else {
        const jsonModelObj = modelItem.toJSON();
        if (jsonModelObj) {
          if (jsonModelObj.attributes) {
            let attrObj = jsonModelObj.attributes.htmlAttributes;

            if (attrObj && (attrObj.classes)) {
              preAttrs = {classes: ['simple-grid'], 'data_type': ''};

              Object.values(attrObj.classes).forEach(function (classname, ind) {
                if (classname.indexOf('type--') >= 0) {
                  const splitVal = classname.replace('type--', '');
                  preAttrs['classes'].push('type--' + splitVal);
                  preAttrs['data_type'] = splitVal;
                } else if (classname.indexOf('ck-widget') < 0) {
                  preAttrs['classes'].push(classname);
                }
              });
            }
          }
        }

        if (preAttrs) {
          opt['class'] = preAttrs['classes'].join(' ');
          opt['data-type'] = preAttrs['data_type'];
        }
      }
      return viewWriter.createContainerElement('div', opt);
    }

    function setViewColumnsClasses(modelElement) {
      let classes = 'simple-grid-layout col-xs-12';
      if (modelElement.getAttribute('class') !== undefined) {
        classes = classes.concat(" ", modelElement.getAttribute('class'));
      }
      if (modelElement.getAttribute('class_right_column') !== undefined) {
        classes = classes.concat(" ", modelElement.getAttribute('class_right_column'));
      }
      if (modelElement.getAttribute('class_left_column') !== undefined) {
        classes = classes.concat(" ", modelElement.getAttribute('class_left_column'));
      }
      return classes;
    }
  }
}
