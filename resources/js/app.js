import './bootstrap';
import 'flowbite';

import ToastComponent from '../../vendor/usernotnull/tall-toasts/resources/js/tall-toasts'
import { SchematicRenderer } from "schematic-renderer";


Alpine.plugin(ToastComponent)

Livewire.start()

window.SchematicRenderer = SchematicRenderer;
