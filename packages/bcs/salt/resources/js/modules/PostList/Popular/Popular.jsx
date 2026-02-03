import { moockApi } from "./Popular.constants";
import { CardPopularRound, CardPopularSquare } from "../components";
import { Container } from "../../../shared";

export default function Popular({ background, indexItem }) {
    return (
        <div
            className="post backgroundImg"
            id={moockApi.id}
            style={{
                backgroundImage: `url(${background?.data})` || null,
                backgroundColor: background?.data || "transparent",
            }}
        >
            <Container>
                <div className="postInfoPopular">
                    <div className="postInfoPopular--title">
                        {moockApi.title}
                    </div>
                    <div className="postInfoPopular--card">
                        {moockApi.data.map((item, index) =>
                            moockApi.type_card === "img_square" ? (
                                <CardPopularSquare
                                    key={item.title + index}
                                    img={item.img}
                                    title={item.title}
                                    description={item.description}
                                />
                            ) : (
                                <CardPopularRound
                                    key={item.title + index}
                                    img={item.img}
                                    title={item.title}
                                    description={item.description}
                                />
                            )
                        )}
                    </div>
                </div>
            </Container>
        </div>
    );
}
