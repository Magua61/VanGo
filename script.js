

document.addEventListener('DOMContentLoaded', function () {
    const termsModal = document.getElementById('termsModal');
    const termsLinks = document.querySelectorAll('.terms-link');
    const privacyModal = document.getElementById('privacyModal');
    const privacyLinks = document.querySelectorAll('.privacy-link');
    const faqModal = document.getElementById('faqModal');
    const faqLinks = document.querySelectorAll('.faq-link');
    const contactModal = document.getElementById('contactModal');
    const contactLinks = document.querySelectorAll('.contact-link');
  
    termsLinks.forEach(link => {
        link.addEventListener('click', () => {
            const bootstrapModal = new bootstrap.Modal(termsModal);
            bootstrapModal.show();
        });
    });

    privacyLinks.forEach(link => {
        link.addEventListener('click', () => {
            const bootstrapModal = new bootstrap.Modal(privacyModal);
            bootstrapModal.show();
        });
    });
    
    faqLinks.forEach(link => {
        link.addEventListener('click', () => {
          const bootstrapModal = new bootstrap.Modal(faqModal);
          bootstrapModal.show();
        });
    });

    contactLinks.forEach(link => {
        link.addEventListener('click', () => {
          const bootstrapModal = new bootstrap.Modal(contactModal);
          bootstrapModal.show();
        });
    });
  });
  