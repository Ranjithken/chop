import SimpleGridEditing from './simplegridediting';
import SimpleGridUI from './simplegridui';
import { Plugin } from 'ckeditor5/src/core';

export default class SimpleGrid extends Plugin {
    static get requires() {
        return [ SimpleGridEditing, SimpleGridUI ];
    }
}
