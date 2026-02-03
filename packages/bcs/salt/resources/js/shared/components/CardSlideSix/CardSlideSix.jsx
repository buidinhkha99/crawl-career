import Slider from "react-slick";
import React, { Suspense } from "react";
import { usePage } from "@inertiajs/react";

import Card from "../Card/Card";
import { Loading } from "../Loading";
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
    slidesToShow: 6,
    slidesToScroll: 6,
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

export default function CardSlideSix({ background, indexItem }) {
    const config = usePage().props.components[indexItem];

    return (
        <Suspense fallback={<Loading />}>
            <div
                className="home-banner card-slide cardSlideSix backgroundImg"
                id={config.id}
                style={{
                    backgroundImage: `url(${background?.data})` || null,
                    backgroundColor: background?.data || "#191919",
                }}
            >
                <Container>
                    <SubHeading background={background}>
                        {config.title}
                    </SubHeading>
                    <Slider {...settings}>
                        {config.components?.length > 0 &&
                            config.components.map((item, index) => (
                                <div key={item.title + item.img + index}>
                                    <Card
                                        className=""
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
