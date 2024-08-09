import ReactDOMServer from 'react-dom/server';
import { createInertiaApp } from '@inertiajs/react'
import createServer from '@inertiajs/server';

createServer((page) =>
    createInertiaApp({
        page,
        render: ReactDOMServer.renderToString,
        resolve: (name) => {
            const pages = import.meta.glob('./Pages/**/*.jsx', { eager: true })
            return pages[`./Pages/${name}.jsx`]
        },
        setup: ({ App, props }) => {
            return <App {...props} />;
        },
    })
);
