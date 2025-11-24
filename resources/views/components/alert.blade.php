@php
    $colors = [
        'success' => 'rgba(16, 185, 129, 0.25)',
        'error'   => 'rgba(239, 68, 68, 0.25)',
        'warning' => 'rgba(245, 158, 11, 0.25)',
        'info'    => 'rgba(59, 130, 246, 0.25)',
    ];

    $icons = [
        'success' => '✔',
        'error'   => '✖',
        'warning' => '⚠',
        'info'    => 'ℹ',
    ];
@endphp

<div class="titan-alert" 
     data-alert>
    
    <div class="titan-alert-icon">{{ $icons[$type] }}</div>

    <div class="titan-alert-message">
        {{ $message }}
    </div>

    <button class="titan-alert-close" data-alert-close>
        ×
    </button>
</div>

<style>
    .titan-alert {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 18px;
        border-radius: 10px;
        backdrop-filter: blur(8px);
        background: {{ $colors[$type] }};
        color: white;
        margin-bottom: 20px;
        border: 1px solid rgba(255,255,255,0.1);
        opacity: 1;
        transition: opacity .3s ease;
    }

    .titan-alert-icon {
        font-size: 1.4rem;
    }

    .titan-alert-message {
        flex: 1;
        font-size: 0.95rem;
    }

    .titan-alert-close {
        background: transparent;
        border: none;
        color: white;
        font-size: 1.2rem;
        cursor: pointer;
        opacity: .7;
        transition: opacity .2s;
    }

    .titan-alert-close:hover {
        opacity: 1;
    }
</style>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("[data-alert-close]").forEach(function(btn) {
        btn.addEventListener("click", function() {
            const alert = this.closest("[data-alert]");
            if(alert){
                alert.style.opacity = "0";
                setTimeout(() => alert.remove(), 300);
            }
        });
    });
});
</script>
