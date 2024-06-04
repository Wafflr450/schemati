import './bootstrap';
import 'flowbite';
// import './bundle';
import { Alpine, Livewire } from '../../vendor/livewire/livewire/dist/livewire.esm';
import ToastComponent from '../../vendor/usernotnull/tall-toasts/resources/js/tall-toasts'
import "schematic-renderer";

Alpine.plugin(ToastComponent)

Livewire.start()


window.SchematicRenderer = SchematicRenderer;



