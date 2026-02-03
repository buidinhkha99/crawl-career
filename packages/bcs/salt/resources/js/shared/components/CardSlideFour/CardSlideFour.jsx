import Slider from "react-slick";
import React, { Suspense } from "react";
import { usePage } from "@inertiajs/react";

import Card from "../Card/Card";
import { Container } from "../../Container";
import { SubHeading } from "../SubHeading";

const settings = {
    dots: true,
    arrows: false,
    adaptiveHeight: true,
    swipe: true,
    autoplay: true,
    infinite: true,
    speed: 500,
    slidesToShow: 4,
    slidesToScroll: 4,
    appendDots: (dots) => (
        <div className="absolute left-0">
            <ul style={{ margin: "0px" }}> {dots} </ul>
        </div>
    ),
    customPaging: (i) => <div className="button-slide" />,
    responsive: [
        {
            breakpoint: 992,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 2,
                initialSlide: 2,
            },
        },
        {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1,
            },
        },
    ],
};

export default function CardSlideFour({ background, indexItem }) {
    const config = usePage().props.components[indexItem];

    return (
        <Suspense fallback={<h1>Loading Slide Four...</h1>}>
            <div
                className="home-banner card-slide cardSlideFour backgroundImg"
                id={config?.id}
                style={{
                    backgroundImage: `url(${background?.data})` || null,
                    backgroundColor: background?.data || "transparent",
                }}
            >
                <Container>
                    <SubHeading background={config.background}>
                        {config.title}
                    </SubHeading>
                    <Slider {...settings}>
                        {config.components?.length > 0 &&
                            config.components.map((item, index) => (
                                <div key={item.title + item.img + index}>
                                    <Card
                                        ellipsis={8}
                                        config={config.components[index]}
                                        background={background}
                                    />
                                </div>
                            ))}
                    </Slider>
                </Container>
            </div>
        </Suspense>
    );
}
