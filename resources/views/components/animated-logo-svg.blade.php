<div class="{{ $class }} ">
    <svg viewBox="410.51 306.79 408.25 364.77" xmlns="http://www.w3.org/2000/svg" fill="url(#grad1)" stroke="#000">
        <defs>
            <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="0%">
                <stop offset="0%" style="stop-color:#c125d3;stop-opacity:1" />
                <stop offset="100%" style="stop-color:#ec4899;stop-opacity:1" />
            </linearGradient>
            <filter id="inset-shadow" x="-50%" y="-50%" width="200%" height="200%">
                <feComponentTransfer in="SourceAlpha">
                    <feFuncA type="table" tableValues="1 0" />
                </feComponentTransfer>
                <feGaussianBlur stdDeviation="3" />
                <feOffset dx="5" dy="5" result="offsetblur" />
                <feFlood flood-color="black" result="color" />
                <feComposite in2="offsetblur" operator="in" />
                <feComposite in2="SourceAlpha" operator="in" />
                <feMerge>
                    <feMergeNode in="SourceGraphic" />
                    <feMergeNode />
                </feMerge>
            </filter>
        </defs>
        <path filter="url(#inset-shadow)" d="m610.54 549.24-86.455-50.085-.311 100.18 86.913 49.821-.311-99.821"
            id="bottom-left-cube-face" />
        <path filter="url(#inset-shadow)" d="m701.28 457.15-86.603-50.358-86.602 50.358 86.602-150.36 86.603 150"
            id="top-arrow" />
        <path filter="url(#inset-shadow)" d="m615.68 540.98 86.603-49.831-86.603-50.358-86.602 50.358 86.602 49.642"
            id="top-cube-face" />
        <path filter="url(#inset-shadow)" d="m645.24 671.38l86.913-49.82-.311-100.18 86.914 150.18h-173.2"
            id="bottom-right-arrow" />
        <path filter="url(#inset-shadow)" d="m619.73 548.7-.146 99.916 86.913-49.821-.311-100.18-86.292 50.17"
            id="bottom-right-cube-face" />
        <path filter="url(#inset-shadow)" d="m497.42 521.38l-.31 100.18 86.913 49.82-173.52.18 86.603-150"
            id="bottom-left-arrow" />
    </svg>
</div>

@once
    @push('scripts')
        <script>
            const orderOfAnimation = [
                "bottom-left-cube-face",
                "top-cube-face",
                "bottom-right-cube-face",
                "bottom-left-arrow",
                "top-arrow",
                "bottom-right-arrow",
            ];
            const cubeFaceAnimation = gsap.timeline({
                repeat: 0,
                repeatDelay: 0.5,
            });

            orderOfAnimation.forEach((id, index) => {
                cubeFaceAnimation.fromTo(
                    `#${id}`, {
                        opacity: 0,
                        scale: 0,
                        transformOrigin: "center center",
                    }, {
                        opacity: 1,
                        scale: 1,
                        duration: 0.5, // Duration of each individual animation
                        ease: "back",
                    },
                    index * 0.1 // Start time of each animation
                );
            });
        </script>
    @endpush
@endonce
