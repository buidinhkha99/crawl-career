import React, { lazy, Suspense } from "react";
import { usePage } from "@inertiajs/react";

import { Container } from "../../Container";

const cardComponents = {
    cardSlide: lazy(() => import("../../../modules/Home/CardSlide/CardSlide")),
    cardTextOnly: lazy(() => import("../CardTextOnly/CardTextOnly")),
    cardImageOnly: lazy(() => import("../CardImageOnly/CardImageOnly")),
    cardContentExpand: lazy(() =>
        import("../CardContentExpand/CardContentExpand")
    ),
    cardInfoIcon: lazy(() => import("../CardInfoIcon/CardIncoIcon")),
};

export default function CardContentOne({ background, indexItem }) {
    const config = usePage().props.components[indexItem];

    return (
        <Suspense fallback={<h1>Loading Content One...</h1>}>
            <div
                className="cardContentOne backgroundImg"
                id={config?.id}
                style={{
                    backgroundImage: `url(${background?.data})` || null,
                    backgroundColor: background?.data || "transparent",
                }}
            >
                <Container>
                    {config.components?.length > 0 &&
                        config.components.map((itemConfig, index) => {
                            let CardRender;
                            if (itemConfig.layout === "only-info") {
                                CardRender = cardComponents.cardTextOnly;
                            }
                            if (itemConfig.layout === "only-image") {
                                CardRender = cardComponents.cardImageOnly;
                            }
                            if (
                                itemConfig.layout === "info-image-2:1" ||
                                itemConfig.layout === "image-info-1:2"
                            ) {
                                CardRender = cardComponents.cardSlide;
                            }
                            if (
                                itemConfig.layout === "info-image-1:1" ||
                                itemConfig.layout === "image-info-1:1"
                            ) {
                                CardRender = cardComponents.cardContentExpand;
                            }
                            if (
                                itemConfig.layout === "image-info_icon-1:1" ||
                                itemConfig.layout === "info_icon-image-1:1"
                            ) {
                                CardRender = cardComponents.cardInfoIcon;
                            }

                            if (!CardRender) {
                                return null;
                            }

                            return (
                                <CardRender
                                    ellipsis={4}
                                    config={itemConfig}
                                    key={config.id + index}
                                />
                            );
                        })}
                </Container>
            </div>
        </Suspense>
    );
}
