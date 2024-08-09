const mockApi = {
  theme: "white",
  components: [
    // Information Sections
    {
      type: "info",
      config: {
        logo: {
          img: "https://cdn.haitrieu.com/wp-content/uploads/2022/06/Logo-Ha-Noi.png",
          text: "BC Solution",
          size: "large",
          font_size: "40px",
          color: "#227C9D",
          position: "horizontal",
          font: "Poppins",
        },
        text: "2022 © Copyright BC Solution. All rights reserved. Hi there!",
        socials: [
          {
            url: "https://www.facebook.com/",
            icon: "facebookIcon",
          },
          {
            url: "youtube.com",
            icon: "youtubeIcon",
          },
          {
            url: "figma.com",
            icon: "figmaIcon",
          },
          {
            url: "twitter.com",
            icon: "twitterIcon",
          },
        ],
      },
    },

    // NavGroup
    {
      type: "navGroup",
      config: {
        title: "Curabitur",
        navs: [
          {
            name: "Fusce convallis",
            url: "https://www.facebook.com/",
          },
          {
            name: "Fusce convallis",
            url: "https://www.facebook.com/",
          },
          {
            name: "Vivamus",
            url: "https://www.facebook.com/",
          },
          {
            name: "Nullam",
            url: "https://www.facebook.com/",
          },
        ],
      },
    },

    // NavGroup
    {
      type: "navGroup",
      config: {
        title: "Curabitur",
        navs: [
          {
            name: "Fusce convallis",
            url: "https://www.facebook.com/",
          },
          {
            name: "Fusce convallis",
            url: "https://www.facebook.com/",
          },
          {
            name: "Vivamus",
            url: "https://www.facebook.com/",
          },
          {
            name: "Nullam",
            url: "https://www.facebook.com/",
          },
        ],
      },
    },

    // Form
    {
      type: "form",
      config: {
        size: "large",
        title: "Contact",
        inputs: [
          {
            icon: "apartmentIcon",
            name: "company_name",
            rules: [
              {
                required: true,
                message: "Please enter your company name",
              },
            ],
            placeholder: "Company (*)",
            type: "text",
          },
          {
            icon: "alternateIcon",
            name: "address",
            rules: [
              {
                required: true,
                message: "Please enter your address",
              },
            ],
            placeholder: "Address (*)",
            type: "text",
          },
          {
            icon: "",
            name: "message",
            rules: [
              {
                required: false,
                message: null,
              },
            ],
            placeholder: "Message",
            type: "textarea",
          },
        ],
        button: {
          icon: "",
          text: "SEND",
          button_type: "submit",
          type_border: "outline",
          size: "large",
          color_background: "#fff",
          color_text: "#227C9D",
        },
      },
    },
  ],
};

export default mockApi;
