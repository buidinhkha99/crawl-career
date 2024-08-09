import imgCard from "../../../../img/imgCard.png";
import avatar from "../../../../img/author.png";

export const mockApi = {
    id:'',
    background:{
        type: 'url',
        data: "#333333"
    },
    theme: "light_blue",
    thumbnail: imgCard,
    author: {
        name: "Amit Das",
        avatar: avatar,
    },
    title: "Excepteur sint occaecat cupidatat 23% non dg à ưng dproident, sunt in culpa qui officia",
    posted_at: "2023-02-09T03:17:05.397Z",
    tags: [
        {
            title: "Lorem",
            color:"red",
            url:""
        },
        {
            title: "eros con",
            color:"green",
            url:""
        },
        {
            title: "et Sed",
            color: "orange",
            url:""
        },
    ],
    content: `An intense way to learn about the process and practice your designs skills — My 1st hackathon Hackathons have been on my mind since I heard it was a good way to gain experience as a junior UX designer. As my portfolio... Lorem ipsum, or lipsum as it is sometimes known, is dummy text used in laying out print, graphic or web designs. The passage is attributed to an unknown typesetter in the 15th century who is thought to have scrambled parts of Cicero's De Finibus Bonorum et Malorum for use in a type specimen book. It usually begins with:
    “Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.”
    The purpose of lorem ipsum is to create a natural looking block of text (sentence, paragraph, page, etc.) that doesn't distract from the layout. A practice not without controversy, laying out pages with meaningless filler text can be very useful when the focus is meant to be on design, not content.
    The passage experienced a surge in popularity during the 1960s when Letraset used it on their dry-transfer sheets, and again during the 90s as desktop publishers bundled the text with their software. Today it's seen all around the web; on templates, websites, and stock designs. Use our generator to get your own, or read on for the authoritative history of lorem ipsum.
`,
};
