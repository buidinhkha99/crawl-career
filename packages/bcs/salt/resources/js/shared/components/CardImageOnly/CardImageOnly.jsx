import React, { Suspense } from "react";
import { Link } from "@inertiajs/react";

import { Container } from "../../Container";

export default function CardImageOnly({ config }) {
    const { config_direct } = config;

    const renderImage = () => {
        if (config_direct) {
            if (config_direct.url && config_direct.url.startsWith("https"))
                return (
                    <a
                        href={config_direct.url}
                        rel="noreferrer"
                        target="_blank"
                        className="block w-full"
                    >
                        <img src={config.img} alt="w-full h-auto" />
                    </a>
                );
            if (config_direct.url && config_direct.url.startsWith("/"))
                return (
                    <Link
                        href={`${config_direct.url}`}
                        className="block w-full"
                    >
                        <img src={config.img} alt="w-full h-auto" />
                    </Link>
                );

            if (config_direct.url && config_direct.url.startsWith("#"))
                return (
                    <a
                        href={config_direct.url}
                        rel="noreferrer"
                        className="block w-full"
                    >
                        <img src={config.img} alt="w-full h-auto" />
                    </a>
                );
        } else {
            return <img src={config.img} alt="w-full h-auto" />;
        }
    };

    return (
        <Suspense fallback={<h1>Loading CardImageOnly ...</h1>}>
            <div id={config.id} className="cardImageOnly">
                <Container>
                    <div className="flex flex-col items-center">
                        {config.img && renderImage()}
                    </div>
                </Container>
            </div>
        </Suspense>
    );
}
