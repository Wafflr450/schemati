import './bootstrap';
import 'flowbite';
import './bundle';
console.log('Hello World from Webpacker');

import { Alpine, Livewire } from '../../vendor/livewire/livewire/dist/livewire.esm';
import ToastComponent from '../../vendor/usernotnull/tall-toasts/resources/js/tall-toasts'

Alpine.plugin(ToastComponent)

Livewire.start()





