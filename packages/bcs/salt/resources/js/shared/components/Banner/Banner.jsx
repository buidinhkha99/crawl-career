import React, { Suspense } from "react";
import { usePage, Link } from "@inertiajs/react";

import { Container } from "../../Container";

export default function Banner({ background, indexItem }) {
    const config = usePage().props.components[indexItem];

    return (
        <Suspense fallback={<h1>Loading Banner</h1>}>
            <div
                id={config.id}
                style={{
                    backgroundImage: `url(${background?.data})` || null,
                    backgroundColor: background?.data || "transparent",
                }}
                className="backgroundImg"
            >
                <Container>
                    {config.url?.startsWith("https") && (
                        <a
                            id={config.id}
                            target="_blank"
                            href={config.url}
                            rel="noopener noreferrer"
                            className="banner__container"
                            style={{
                                backgroundImage:
                                    `url(${config.background?.data})` || null,
                                backgroundColor:
                                    config.background?.data || "transparent",
                            }}
                        >
                            <img src={config.img} className="banner__img" />
                        </a>
                    )}

                    {config.url?.startsWith("/") && (
                        <Link
                            id={config.id}
                            target="_blank"
                            rel="noopener noreferrer"
                            href={config.background.url}
                            className="banner__container"
                            style={{
                                backgroundImage:
                                    `url(${background?.data})` || null,
                                backgroundColor: background?.data || "transparent",
                            }}
                        >
                            <img src={config.img} className="banner__img" />
                        </Link>
                    )}

                    {config.url?.startsWith("#") && config.url && (
                        <a
                            id={config.id}
                            rel="noopener noreferrer"
                            href={config.background.url}
                            className="banner__container"
                            style={{
                                backgroundImage:
                                    `url(${background?.data})` || null,
                                backgroundColor: background?.data || "transparent",
                            }}
                        >
                            <img src={config.img} className="banner__img" />
                        </a>
                    )}
                </Container>
            </div>
        </Suspense>
    );
}
