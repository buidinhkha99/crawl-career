import { moockApi } from "./Topic.constants";
import { Tag } from "../components/Tag";
import { Container } from "../../../shared";

export default function Topic({ background, indexItem }) {
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
                <div className="postInfoTopic">
                    <div className="postInfoTopic--title">{moockApi.title}</div>
                    <div className="postInfoTopic__div--tag">
                        {moockApi.tags?.map((tagTopic, index) => {
                            return (
                                <Tag
                                    key={tagTopic.title + index}
                                    color={tagTopic.color || "#4C94AF"}
                                    title={tagTopic.title}
                                    className="mb-5"
                                />
                            );
                        })}
                    </div>
                </div>
            </Container>
        </div>
    );
}
